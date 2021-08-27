<?php

namespace healfeedui;

use pocketmine\Player;
use pocketmine\Server;

use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class Main extends PluginBase implements Listener{
    
    public function onEnable(){
        $this->getLogger()->info("§aPlugin HealFeedUI enable");
        $this->getServer()->getPluginManager()->registerEvents($this,$this);
    }

    public function onLoad(){
        $this->getLogger()->info("§eLoading HealFeedUI...");
    }

    public function onDisable(){
        $this->getLogger()->info("§cDisabling HealFeedUI, FormAPI Don't Detected");
    }
    
    public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
        switch($cmd->getName()){
            case "hfui":
                if($sender instanceof Player){
                    if($sender->hasPermission("use.hfui")){
                        $this->hfui($sender);
                    }
                    return true;
                }else{
                    $sender->sendMessage("§cUse Command In Game");
                }
            break;
        }
        return true;
    }
    
    public function hfui($player){
        $api = $this->getServer()->getPluginManager()->getPlugin("FormAPI");
        $form = $api->createSimpleForm(function(Player $player, int $data = null) {
            $result = $data;
            if($result == null){
                return true;
            }
            switch($result){
                case 0:
                    $player->addTitle("§cGOODBYE", "HealFeedUI");
                break;

                case 1:
                    $player->setHealth(20);
                    $player->sendMessage($this->getConfig()->get("MSG-HEAL"));
                break;

                case 2:
                    $player->setFood(20);
                    $player->sendMessage($this->getConfig()->get("MSG-FEED"));
                break;

            }
        });
        $form->setTitle($this->getConfig()->get("TITLE-UI"));
        $form->setContent($this->getConfig()->get("CONTENT-UI"));
        $form->addButton("§c§lExit", 0, "textures/ui/cancel");
        $form->addButton($this->getConfig()->get("BTN-HEAL"), 0, "textures/items/feather");
        $form->addButton($this->getConfig()->get("BTN-FEED"), 0, "textures/items/book_writable");
        $form->sendToPlayer($player);
        return $form;
    }
}
