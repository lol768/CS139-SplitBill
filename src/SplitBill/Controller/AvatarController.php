<?php

namespace SplitBill\Controller;

use SplitBill\Authentication\IAuthenticationManager;
use SplitBill\DependencyInjection\IContainer;
use SplitBill\Helper\IControllerHelper;
use SplitBill\IApplication;
use SplitBill\Repository\IUserRepository;
use SplitBill\Request\HttpRequest;
use SplitBill\Response\RedirectResponse;
use SplitBill\Session\IFlashSession;

class AvatarController extends AbstractController {

    /**
     * @var IControllerHelper The controller helper instance.
     */
    private $h;
    /**
     * @var IApplication
     */
    private $app;
    /**
     * @var IAuthenticationManager
     */
    private $authMan;


    public function __construct(IControllerHelper $helper, IApplication $app, IAuthenticationManager $authMan) {
        $this->h = $helper;
        $this->h->requireLoggedIn();
        $this->app = $app;
        $this->authMan = $authMan;
    }

    /**
     * POST upload_avatar.php
     * @param HttpRequest $request (Have to do this manually, no support in codebase)
     * @param IFlashSession $flash
     * @return RedirectResponse
     */
    public function postUploadAvatar(HttpRequest $request, IFlashSession $flash, IUserRepository $userRepo) {
        $uid = $this->authMan->getEffectiveUser()->getUserId();
        $files = $request->getUploadedFiles();
        if (!array_key_exists("avatar", $files)) {
            $flash->set("errors", array("You didn't upload an avatar."));
            return new RedirectResponse("edit_profile.php");
        }
        $avatarFile = $files['avatar'];
        var_dump($avatarFile);
        if ($avatarFile['type'] === "image/png") {
            $image = imagecreatefrompng($avatarFile['tmp_name']);
        } else if ($avatarFile['type'] === "image/jpeg" || $avatarFile['type'] === "image/jpg") {
            $image = imagecreatefromjpeg($avatarFile['tmp_name']);
        } else {
            $flash->set("errors", array("Unrecognised image type. Ensure it's a PNG or JPEG and try again."));
            return new RedirectResponse("edit_profile.php");
        }

        if ($image === false) {
            $flash->set("errors", array("Error loading avatar. Ensure it's a valid image file and try again."));
            return new RedirectResponse("edit_profile.php");
        }
        $sizeInfo = getimagesize($avatarFile['tmp_name']);
        if ($sizeInfo[0] !== $sizeInfo[1]) {
            $flash->set("errors", array("Avatar must be square."));
            return new RedirectResponse("edit_profile.php");
        }

        if ($sizeInfo[0] < 200) {
            $flash->set("errors", array("Avatar must be at least 200x200."));
            return new RedirectResponse("edit_profile.php");
        }

        $thumbnailImage = imagecreatetruecolor(36, 36);
        imagecopyresampled($thumbnailImage, $image, 0, 0, 0, 0, 36, 36, $sizeInfo[0], $sizeInfo[1]);

        $largerImage = imagecreatetruecolor(200, 200);
        imagecopyresampled($largerImage, $image, 0, 0, 0, 0, 200, 200, $sizeInfo[0], $sizeInfo[1]);

        imagepng($thumbnailImage, $this->app->getRootPath() . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "avatars" . DIRECTORY_SEPARATOR . $uid . "_t.png");
        imagepng($largerImage, $this->app->getRootPath() . DIRECTORY_SEPARATOR . "assets" . DIRECTORY_SEPARATOR . "avatars" . DIRECTORY_SEPARATOR . $uid . ".png");
        imagedestroy($image);
        imagedestroy($thumbnailImage);
        imagedestroy($largerImage);
        $flash->set("flash", array("type" => "success", "message" => "Avatar uploaded."));
        $this->authMan->getEffectiveUser()->setHasAvatar(true);
        $userRepo->update($this->authMan->getEffectiveUser());
        return new RedirectResponse("edit_profile.php");
    }

}
