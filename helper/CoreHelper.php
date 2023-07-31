<?php

/**
 * Class CoreHelper
 *
 * @author Marco Bax <marco@ohmydish.nl>
 */
class CoreHelper
{
    /**
     * @param array $elements
     * @param int $parent_id
     * @return array
     */
    public static function buildTree(array &$elements, $parent_id = 0): array
    {
        $branch = [];

        foreach ($elements as $element) {
            if ($element['parent_id'] == $parent_id) {
                $children = self::buildTree($elements, $element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[$element['id']] = $element;
                unset($elements[$element['id']]);
            }
        }
        return $branch;
    }

    /**
     * @param string $subject
     * @return string
     */
    public static function slugify(string $subject): string
    {
        setlocale(LC_ALL, "en_US.utf8");
        $subject = iconv(mb_detect_encoding($subject), "ASCII//TRANSLIT//IGNORE", $subject);
        $subject = str_replace(' -', '', $subject);
        $subject = preg_replace('/[^A-Za-z0-9-\w ]+/', '', $subject);

        if (strlen($subject)) {
            return strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $subject), '-'));
        }

        return $subject;
    }

    /**
     * @param string $string
     * @param bool $salt
     *
     * @return array
     */
    public static function hash(string $string, $salt = false): array {
        if (false === $salt) {
            $salt = sprintf('$2y$%02d$%s', 12, substr(strtr(base64_encode(openssl_random_pseudo_bytes(18)), '+', '.'), 0, 22));
        }

        return [
            'value' => crypt($string, $salt),
            'salt'  => $salt
        ];
    }

    static public function createThumbnail($src, $dest, $targetWidth, $targetHeight = null)
    {
        // 1. Load the image from the given $src
        // - see if the file actually exists
        // - check if it's of a valid image type
        // - load the image resource

        // get the type of the image
        // we need the type to determine the correct loader
        $type = exif_imagetype($src);

        // if no valid type or no handler found -> exit
        if (!$type || !IMAGE_HANDLERS[$type]) {
            return null;
        }

        // load the image with the correct loader
        $image = call_user_func(IMAGE_HANDLERS[$type]['load'], $src);

        // no image found at supplied location -> exit
        if (!$image) {
            return null;
        }

        // 2. Create a thumbnail and resize the loaded $image
        // - get the image dimensions
        // - define the output size appropriately
        // - create a thumbnail based on that size
        // - set alpha transparency for GIFs and PNGs
        // - draw the final thumbnail

        // get original image width and height
        $width = imagesx($image);
        $height = imagesy($image);

        // maintain aspect ratio when no height set
        if ($targetHeight == null) {

            // get width to height ratio
            $ratio = $width / $height;

            // if is portrait
            // use ratio to scale height to fit in square
            if ($width > $height) {
                $targetHeight = floor($targetWidth / $ratio);
            }
            // if is landscape
            // use ratio to scale width to fit in square
            else {
                $targetHeight = $targetWidth;
                $targetWidth = floor($targetWidth * $ratio);
            }
        }

        // create duplicate image based on calculated target size
        $thumbnail = imagecreatetruecolor($targetWidth, $targetHeight);

        // set transparency options for GIFs and PNGs
        if ($type == IMAGETYPE_GIF || $type == IMAGETYPE_PNG) {

            // make image transparent
            imagecolortransparent(
                $thumbnail,
                imagecolorallocate($thumbnail, 0, 0, 0)
            );

            // additional settings for PNGs
            if ($type == IMAGETYPE_PNG) {
                imagealphablending($thumbnail, false);
                imagesavealpha($thumbnail, true);
            }
        }

        // copy entire source image to duplicate image and resize
        imagecopyresampled(
            $thumbnail,
            $image,
            0, 0, 0, 0,
            $targetWidth, $targetHeight,
            $width, $height
        );


        // 3. Save the $thumbnail to disk
        // - call the correct save method
        // - set the correct quality level

        // save the duplicate version of the image to disk
        return call_user_func(
            IMAGE_HANDLERS[$type]['save'],
            $thumbnail,
            $dest,
            IMAGE_HANDLERS[$type]['quality']
        );
    }

    public static function generate_filename($path, $filename)
    {
        $ext = '';

        if ($pos = strrpos($filename, '.')) {
            $name = substr($filename, 0, $pos);
            $ext = substr($filename, $pos);
        } else {
            $name = $filename;
        }

        $name = CoreHelper::slugify($name);

        $newpath = $path . '/' . $filename;
        $newname = $name . $ext;
        $counter = 0;

        while(file_exists($newpath)) {
            $newname = $name . '-' . $counter . $ext;
            $newpath = $path . '/' . $newname;

            $counter++;
        }

        return $newname;
    }
}