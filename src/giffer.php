<?php

include ('GifFrameExtractor.php');
include ('GifCreator.php');



$shortoptions = "w::h::m::";
$longoptions = array("2" => "width::", "4" => "height::", "6" => "mark::", "8" => "src:", "10" => "dest:");
$options = getopt($shortoptions, $longoptions);



$width = $argv[2];
$height = $argv[4];
$mark = $argv[6];
$src = $argv[8];
$dest = $argv[10];

$sourceImageSize = getimagesize($src);
$sourceImageWidth = $sourceImageSize[0];
$sourceImageHeight = $sourceImageSize[1];

$waterMarkSize = getimagesize($mark);
$waterMarkWidth = $waterMarkSize[0];
$waterMarkHeight = $waterMarkSize[1];

$frames = array();
$durations = array();
$gc = new GifCreator();

function resize($width, $height, $sourceImageWidth, $sourceImageHeight) {

    if ($width == 0 && $height == 0) {

        return array($sourceImageWidth, $sourceImageHeight);

    } else if ($width == 0) {
        
        $newWidth = $sourceImageWidth / ($sourceImageHeight / $height);
        return array($newWidth, $height);
    
    } else if ($height == 0) {
        
        $newHeight = $sourceImageHeight / ($sourceImageWidth / $width);
        return array($width, $newHeight);
    
    } else {

        return array($width, $height);
    
    }

}

if (GifFrameExtractor::isAnimatedGif($src)) {

    mkdir('../img/temp');
    $gif = new GifFrameExtractor();
    $gif->extract($src);
    $frameNumber = 0;
    
    foreach ($gif->getFrames() as $frame) {

        $resizedFrame = resize($width, $height, $sourceImageWidth, $sourceImageHeight);

        $resized = imagecreatetruecolor($resizedFrame[0], $resizedFrame[1]);
        $watermark = imagecreatefrompng($mark);
        imagecopyresampled(
            $resized, $frame['image'],
            0, 0, 0, 0,
            $resizedFrame[0], $resizedFrame[1],
            $sourceImageWidth, $sourceImageHeight,
            );
        imagecopymerge(
            $resized, $watermark,
            $resizedFrame[0] / 2 - ($waterMarkWidth / 2), $resizedFrame[1] / 2 - ($waterMarkHeight / 2), 0, 0,
            $waterMarkWidth, $waterMarkHeight,                
            30
        );

        imagegif($resized, '../img/temp/' . $frameNumber . ' - frame' . '.gif');
        array_push($frames, '../img/temp/' . $frameNumber . ' - frame' . '.gif');
        array_push($durations, $frame['duration']);
        $frameNumber++;
    }

    $gc->create($frames, $durations, 0);
    $gifBinary = $gc->getGif();
    file_put_contents($dest, $gifBinary);

    foreach ($frames as $item) {
        unlink($item);

    }

    rmdir('../img/temp');

}

?>