<?php

/**
 * Copyright (c) 2018 yuko fuyutsuki < https://github.com/fuyutsuki >
 *
 * このソフトウェアは"MITライセンス"下で配布されています。
 * あなたはこのプログラムと共にMITライセンスのコピーを受け取ったはずです。
 * 受け取っていない場合、下記のURLからご覧ください。
 * < https://opensource.org/licenses/mit-license >
 */

namespace tokyo\pmmp\YV;


use pocketmine\{
  entity\Skin,
  event\Listener,
  event\player\PlayerJoinEvent,
  plugin\PluginBase
};

class Cell extends PluginBase implements Listener {

  public const CODENAME = "YV-01";
  public const DS = DIRECTORY_SEPARATOR;
  public const PREFIX = "[YV] ";

  /** @var Skin */
  private $skin = null;

  public function onEnable() {
    $this->getServer()->getPluginManager()->registerEvents($this, $this);
    $dir = $this->getDataFolder();
    if (!file_exists($dir."yv-01.png")) {
      mkdir($dir);
      $data = file_get_contents("https://textures.minecraft.net/texture/9b542314caecac91ba2abf7566e6fc2c01c913e9d4dca826d7055905f13aeb3");
      file_put_contents($dir."yv-01.png", $data);
    }
    $image = imagecreatefrompng($this->getDataFolder() . "yv-01.png");
    $data = '';
    $height = imagesy($image);
    $width = imagesx($image);
    for ($y = 0; $y < $height; $y++) {
      for ($x = 0; $x < $width; $x++) {
        $color = imagecolorat($image, $x, $y);
        $data .= pack("c", ($color >> 16) & 0xFF) //red
          . pack("c", ($color >> 8) & 0xFF) //green
          . pack("c", $color & 0xFF) //blue
          . pack("c", 255 - (($color & 0x7F000000) >> 23)); //alpha
      }
    }
    $this->skin = new Skin("yv-01", $data);
  }

  public function onJoin(PlayerJoinEvent $e) {
    $p = $e->getPlayer();
    $p->setSkin($this->skin);
    $p->sendSkin($this->getServer()->getOnlinePlayers());
    $p->setNameTag("yuko fuyutsuki");
    $p->setDisplayName("yuko fuyutsuki");
  }
}