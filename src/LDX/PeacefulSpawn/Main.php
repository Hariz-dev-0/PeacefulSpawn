<?php
namespace LDX\PeacefulSpawn;
use pocketmine\math\Vector3;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\TextFormat;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
class Main extends PluginBase implements Listener {
  public function onEnable() {
    $this->enabled = true;
    $this->getServer()->getPluginManager()->registerEvents($this,$this);
  }
  public function onCommand(CommandSender $issuer,Command $cmd,$label,array $args) {
    if(strtolower($cmd->getName()) == "ps" ) {
      if($issuer->hasPermission("peacefulspawn") || $issuer->hasPermission("peacefulspawn.toggle")) {
        $this->enabled = !$this->enabled;
        if($this->enabled) {
          $issuer->sendMessage("[PeacefulSpawn] Spawn protection enabled!");
          $this->getLogger()->info(TextFormat::YELLOW . "Spawn protection enabled!");
        } else {
          $issuer->sendMessage("[PeacefulSpawn] Spawn protection disabled!");
          $this->getLogger()->info(TextFormat::YELLOW . "Spawn protection disabled!");
        }
      } else {
        $issuer->sendMessage("You do not have permission to toggle spawn protection.");
      }
      return true;
    } else {
      return false;
    }
  }
  /**
  * @param EntityDamageEvent $event
  *
  * @priority HIGHEST
  * @ignoreCancelled true
  */
  public function onHurt(EntityDamageEvent $event) {
    $entity = $event->getEntity();
    $v = new Vector3($entity->getLevel()->getSpawnLocation()->getX(),$entity->getPosition()->getY(),$entity->getLevel()->getSpawnLocation()->getZ());
    $r = $this->getServer()->getSpawnRadius();
    if(($entity instanceof Player) && ($entity->getPosition()->distance($v) <= $r) && ($this->enabled == true)) {
      $event->setCancelled();
    }
  }
  /**
  * @param EntityDamageByEntityEvent $event
  *
  * @priority HIGHEST
  * @ignoreCancelled true
  */
  public function onHurt(EntityDamageByEntityEvent $event) {
    $entity = $event->getEntity();
    $v = new Vector3($entity->getLevel()->getSpawnLocation()->getX(),$entity->getPosition()->getY(),$entity->getLevel()->getSpawnLocation()->getZ());
    $r = $this->getServer()->getSpawnRadius();
    if(($entity instanceof Player) && ($entity->getPosition()->distance($v) <= $r) && ($this->enabled == true)) {
      $event->setCancelled();
    }
  }
}
?>
