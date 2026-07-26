<?php

namespace App\Http\Services\Image;

use Illuminate\Support\Facades\Config;
use Intervention\Image\Facades\Image;

class ImageService extends ImageToolsService
{

    public function save($image)
    {
        $this->setImage($image);
        $this->provider();

        $extension = strtolower($image->getClientOriginalExtension());

        if ($extension === 'gif') {
            // فقط کپی کن، پردازش نکن
            $result = $image->move(
                public_path(dirname($this->getImageAddress())),
                basename($this->getImageAddress())
            );

            return $result ? $this->getImageAddress() : false;
        }

        // نسخه صحیح برای Intervention Image v2
        $result = Image::make($image->getRealPath())
            ->save(public_path($this->getImageAddress()), null, $this->getImageFormat());

        return $result ? $this->getImageAddress() : false;
    }



    public function fitAndSave($image, $width, $height)
    {
        // set image
        $this->setImage($image);

        // execute provider
        $this->provider();

        //save image
        $result = Image::make($image->getRealPath())->fit($width, $height)->save(public_path($this->getImageAddress()), null, $this->getImageFormat());
        return $result ? $this->getImageAddress() : false;
    }

    public function createIndexAndSave($image)
    {
        // get data from config
        $imageSizes = Config::get('image.index-image-sizes');

        // set image
        $this->setImage($image);

        // set directory
        $this->getImageDirectory() ?? $this->setImageDirectory(date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR . date('d'));
        $this->getImageName() ?? $this->setImageName(time());

        // set name
        $this->getImageName() ?? $this->setImageName(time());
        $imageName = $this->getImageName();

        $indexArray = [];
        foreach ($imageSizes as $sizeAlias => $imageSize) {
            // create and set this size name
            $currentImageName = $imageName . '_' . $sizeAlias;
            $this->setImageName($currentImageName);

            // execute provider
            $this->provider();

            // save image
            $result = Image::make($image->getRealPath())->fit($imageSize['width'], $imageSize['height'])->save(public_path($this->getImageAddress()), null, $this->getImageFormat());
            if ($result)
                $indexArray[$sizeAlias] = $this->getImageAddress();
            else {
                return false;
            }
        }
        $images['indexArray'] = $indexArray;
        $images['directory'] = $this->getFinalImageDirectory();
        $images['currentImage'] = Config::get('image.default-current-index-image');

        return $images;
    }


    public function createBlogImagesAndSave($image)
    {
        $imageSizes = Config::get('image.blog-image-sizes');

        $this->setImage($image);

        $this->getImageDirectory()
            ?? $this->setImageDirectory(date('Y') . DIRECTORY_SEPARATOR . date('m') . DIRECTORY_SEPARATOR . date('d'));

        $this->getImageName() ?? $this->setImageName(time());
        $imageName = $this->getImageName();

        $imagesArray = [];

        foreach ($imageSizes as $sizeAlias => $imageSize) {

            $currentImageName = $imageName . '_' . $sizeAlias;
            $this->setImageName($currentImageName);

            $this->provider();

            $result = Image::make($image->getRealPath())
                ->fit($imageSize['width'], $imageSize['height'])
                ->save(
                    public_path($this->getImageAddress()),
                    null,
                    $this->getImageFormat()
                );

            if (!$result) {
                return false;
            }

            $imagesArray[$sizeAlias] = $this->getImageAddress();
        }

        return [
            'blogArray'   => $imagesArray,
            'directory'   => $this->getFinalImageDirectory(),
            'currentImage' => Config::get('image.default-current-blog-image'),
        ];
    }



    public function deleteImage($imagePath)
    {
        if (file_exists($imagePath)) {
            unlink($imagePath);
        }
    }

    public function deleteIndex($images)
    {
        $directory = public_path($images['directory']);
        $this->deleteDirectoryAndFiles($directory);
    }

    public function deleteDirectoryAndFiles($directory)
    {
        if (!is_dir($directory)) {
            return false;
        }

        $files = glob($directory . DIRECTORY_SEPARATOR . '*', GLOB_MARK);

        foreach ($files as $file) {

            if (is_dir($file)) {
                $this->deleteDirectoryAndFiles($file);
            } else {
                unlink($file);
            }
        }
        $result = rmdir($directory);
        return $result;
    }


    public function deleteIndexFiles(array $indexArray)
    {
        foreach ($indexArray as $path) {
            $filePath = public_path($path);
            if (file_exists($filePath)) {
                unlink($filePath);
            }
        }
    }
}
