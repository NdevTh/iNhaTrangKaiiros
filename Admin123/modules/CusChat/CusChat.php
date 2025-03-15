<?php
use classes\Module as Module;
use Bundles\classes\WebCore as  RTTCore;
use classes\Tools as Tools;
use classes\myUser as myUser;
use classes\myMessage as myMessage;

class CusChat extends Module
{
    public static function hookCusChat($args)
    {
        global $CusUser,$AdminUser;
        myUser::init();
        myMessage::init();
        
        $records = myUser::getRecords();
        $DesUser = array();
        $messages = myMessage::getByWhere('sender="'.$AdminUser['full_name'].'" OR receiver="'.$AdminUser['full_name'].'" ORDER BY date_upd DESC');
        $currentDate = date('Y-m-d H:i:s',time());
        
        if (Tools::getValue('id')){
            $DesUser = myUser::getById(Tools::getValue('id'))[0];
            $messages = myMessage::getByWhere('subject = "'.$AdminUser['id_user'].'_'.$DesUser['id_user'].'" OR subject = "'.$DesUser['id_user'].'_'.$AdminUser['id_user'].'" ORDER BY date_upd ASC');
        }else{
            if (!empty($messages) && $messages[0]['receiver'] != $AdminUser['full_name']){
                $DesUser = myUser::getByWhere('full_name="'.$messages[0]['receiver'].'"')[0];
                $messages = myMessage::getByWhere('subject = "'.$AdminUser['id_user'].'_'.$DesUser['id_user'].'" OR subject = "'.$DesUser['id_user'].'_'.$AdminUser['id_user'].'" ORDER BY date_upd ASC');
            }else if (!empty($messages)) {
                $DesUser = myUser::getByWhere('full_name="'.$messages[0]['sender'].'"')[0];
                $messages = myMessage::getByWhere('subject = "'.$AdminUser['id_user'].'_'.$DesUser['id_user'].'" OR subject = "'.$DesUser['id_user'].'_'.$AdminUser['id_user'].'" ORDER BY date_upd ASC');
            }
        }
        if (Tools::isSubmit('btnSubmitAdd')){
            /*$ret.= '<div class="msg">';
            $ret.= '   Action "Send" was successful';
            $ret.= '</div>';*/
            $_POST["date_add"] = $currentDate;
            $_POST["date_upd"] = $currentDate;
            //$_POST["type"]     = 1; //type : 1 create, 2 reply, 3 closed
            $_POST["readed"]   = 1; 
            $_POST["active"]   = 1;
            if (Tools::getValue('id')){
                $fields = array('type','readed');
                myMessage::updateByFields(Tools::getValue('id'), $fields);
            }
            $_POST["readed"]   = 0;
            $insert_id = myMessage::save();
            $record = myMessage::getById($insert_id)[0];
        }
        
        $ret = "";
        //$ret.= "Hook: CusChat";
        $ret.= '<div id="container">';
        $ret.= '    <aside>';
        $ret.= '        <header>';
        $ret.= '            <input style="height:20px;" id="txtchart" type="text" placeholder="'.RTTCore::l('Search','admin') .'">';
        $ret.= '        </header>';
        
        $ret.= '        <ul>';
        if ($records)
        {
            foreach($records as $record)
            {
                $ret.= '            <li><a href="index.php?t='.Tools::getValue('t').'&id='.$record["id_user"].'&token='.Tools::getValue('token').'" >';
                $ret.= '                <img src="'.((!empty($record) && array_key_exists('img_name',$record) && !empty($record["img_name"]))?"images/u/".$record["img_name"]:'https://s3-us-west-2.amazonaws.com/s.cdpn.io/1940306/chat_avatar_01.jpg').'" alt="">';
                $ret.= '                <div>';
                $ret.= '                    <h2>'.((!empty($record) && array_key_exists('full_name',$record))?$record["full_name"]:"Unknown").'</h2>';
                $ret.= '                    <h3>';
                $ret.= '                        <span class="status '.((!empty($record) && array_key_exists('status',$record) && ($record["status"] >= 1))?"green":"orange").'"></span>';
                $ret.= '                        '.((!empty($record) && array_key_exists('status',$record) && ($record["status"] >= 1))?RTTCore::l('online','admin'):RTTCore::l('offline','admin')).'';
                $ret.= '                    </h3>';
                $ret.= '                </div>';
                $ret.= '            </a></li>';
        
            }
        }else{
        $ret.= '            <li>';
        $ret.= '                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/1940306/chat_avatar_01.jpg" alt="">';
        $ret.= '                <div>';
        $ret.= '                    <h2>Prénom Nom</h2>';
        $ret.= '                    <h3>';
        $ret.= '                        <span class="status orange"></span>';
        $ret.= '                        offline';
        $ret.= '                    </h3>';
        $ret.= '                </div>';
        $ret.= '            </li>';
        
        $ret.= '            <li>';
        $ret.= '                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/1940306/chat_avatar_02.jpg" alt="">';
        $ret.= '                <div>';
        $ret.= '                    <h2>Prénom Nom</h2>';
        $ret.= '                    <h3>';
        $ret.= '                        <span class="status green"></span>';
        $ret.= '                        online';
        $ret.= '                    </h3>';
        $ret.= '                </div>';
        $ret.= '            </li>';
        
        $ret.= '            <li>';
        $ret.= '                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/1940306/chat_avatar_04.jpg" alt="">';
        $ret.= '                <div>';
        $ret.= '                    <h2>Prénom Nom</h2>';
        $ret.= '                    <h3>';
        $ret.= '                        <span class="status green"></span>';
        $ret.= '                        online';
        $ret.= '                    </h3>';
        $ret.= '                </div>';
        $ret.= '            </li>';
        
        $ret.= '            <li>';
        $ret.= '                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/1940306/chat_avatar_01.jpg" alt="">';
        $ret.= '                <div>';
        $ret.= '                    <h2>Prénom Nom</h2>';
        $ret.= '                    <h3>';
        $ret.= '                        <span class="status orange"></span>';
        $ret.= '                        offline';
        $ret.= '                    </h3>';
        $ret.= '                </div>';
        $ret.= '            </li>';
        
        $ret.= '            <li>';
        $ret.= '                <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/1940306/chat_avatar_05.jpg" alt="">';
        $ret.= '                <div>';
        $ret.= '                    <h2>Prénom Nom</h2>';
        $ret.= '                    <h3>';
        $ret.= '                        <span class="status green"></span>';
        $ret.= '                        online';
        $ret.= '                    </h3>';
        $ret.= '                </div>';
        $ret.= '            </li>';
        }
        $ret.= '        </ul>';
        $ret.= '    </aside>';
        
        
        $ret.= '    <main>';
        $ret.= '        <header>';
        $ret.= '            <img src="'.((!empty($DesUser) && array_key_exists('img_name',$DesUser) && !empty($DesUser["img_name"]))?"images/u/".$DesUser["img_name"]:'https://s3-us-west-2.amazonaws.com/s.cdpn.io/1940306/chat_avatar_01.jpg').'" alt="">';
        $ret.= '            <div>';
        $ret.= '                <h2>'.RTTCore::l('Chat With','admin') .' '.((!empty($DesUser) && array_key_exists('full_name',$DesUser) && !empty($DesUser["full_name"]))?$DesUser["full_name"]:'Unknown').'</h2>';
        $ret.= '                <h3>'.RTTCore::l('already','admin') .' '.count($messages).' '.RTTCore::l('messages','admin') .'</h3>';
        $ret.= '            </div>';
        $ret.= '            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/1940306/ico_star.png" alt="">';
        $ret.= '        </header>';
        
        $ret.= '        <ul id="chat">';
        if ($messages){
            $you = $messages[0]['sender'];
            foreach($messages as $message){
                if ($message['sender'] == $you ){
                    $ret.= '           <li class="you">';
                    $ret.= '               <div class="entete">';
                    $ret.= '                   <span class="status green"></span>';
                    $ret.= '                   <h2>'.((!empty($message) && array_key_exists('sender',$message) && !empty($message["sender"]))?$message["sender"]:'Unknown').'</h2>';
                    $ret.= '                   <h3>'.((!empty($message) && array_key_exists('date_add',$message) && !empty($message["date_add"]))?date("d-m-Y H:i:s",strtotime($message["date_add"])):$currentDate).', Today</h3>';
                    $ret.= '               </div>';
                    $ret.= '               <div class="triangle"></div>';
                    $ret.= '               <div class="message">';
                    $ret.= '                   '.((!empty($message) && array_key_exists('description',$message) && !empty($message["description"]))?$message["description"]:'Unknown').'';
                    $ret.= '               </div>';
                    $ret.= '           </li>';
                }else{
                    $ret.= '           <li class="me">';
                    $ret.= '               <div class="entete">';
                    $ret.= '                   <h3>'.((!empty($message) && array_key_exists('date_add',$message) && !empty($message["date_add"]))?date("d-m-Y H:i:s",strtotime($message["date_add"])):$currentDate).'</h3>';
                    $ret.= '                   <h2>'.((!empty($message) && array_key_exists('sender',$message) && !empty($message["sender"]))?$message["sender"]:"").'</h2>';
                    $ret.= '                   <span class="status blue"></span>';
                    $ret.= '               </div>';
                    $ret.= '               <div class="triangle"></div>';
                    $ret.= '               <div class="message">';
                    $ret.= '                   '.((!empty($message) && array_key_exists('description',$message) && !empty($message["description"]))?$message["description"]:$currentDate).'';
                    $ret.= '               </div>';
                    $ret.= '           </li>';
                }
            }
        }else{
        $ret.= '           <li class="me">';
        $ret.= '               <div class="entete">';
        $ret.= '                   <h3>10:12AM, Today</h3>';
        $ret.= '                   <h2>Vincent</h2>';
        $ret.= '                   <span class="status blue"></span>';
        $ret.= '               </div>';
        $ret.= '               <div class="triangle"></div>';
        $ret.= '               <div class="message">';
        $ret.= '                   OK';
        $ret.= '               </div>';
        $ret.= '           </li>';
        
        $ret.= '           <li class="you">';
        $ret.= '               <div class="entete">';
        $ret.= '                   <span class="status green"></span>';
        $ret.= '                   <h2>Vincent</h2>';
        $ret.= '                   <h3>10:12AM, Today</h3>';
        $ret.= '               </div>';
        $ret.= '               <div class="triangle"></div>';
        $ret.= '               <div class="message">';
        $ret.= '                   Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.';
        $ret.= '               </div>';
        $ret.= '           </li>';
					    
        $ret.= '           <li class="me">';
        $ret.= '               <div class="entete">';
        $ret.= '                   <h3>10:12AM, Today</h3>';
        $ret.= '                   <h2>Vincent</h2>';
        $ret.= '                   <span class="status blue"></span>';
        $ret.= '               </div>';
        $ret.= '               <div class="triangle"></div>';
        $ret.= '               <div class="message">';
        $ret.= '                   Lorem ipsum dolor sit amet, consectetuer adipiscing elit. Aenean commodo ligula eget dolor.';
        $ret.= '               </div>';
        $ret.= '           </li>';
        
        $ret.= '           <li class="me">';
        $ret.= '               <div class="entete">';
        $ret.= '                   <h3>10:12AM, Today</h3>';
        $ret.= '                   <h2>Vincent</h2>';
        $ret.= '                   <span class="status blue"></span>';
        $ret.= '               </div>';
        $ret.= '               <div class="triangle"></div>';
        $ret.= '               <div class="message">';
        $ret.= '                   OK';
        $ret.= '               </div>';
        $ret.= '           </li>';
        }
        $ret.= '        </ul>';
        $ret.= '        <footer><form action="index.php?'.Tools::newUrl('id',(!empty($DesUser)?$DesUser["id_user"]:"")).'" method="post">';
        $ret.= '            <input name="sender" type="hidden" value="'.(!empty($AdminUser)?$AdminUser["full_name"]:"").'"/>';
        $ret.= '            <input name="receiver" type="hidden" value="'.(!empty($DesUser)?$DesUser["full_name"]:"").'"/>';
        $ret.= '            <input name="subject" type="hidden" value="'.(!empty($messages)?$messages[0]["subject"]:($AdminUser["id_user"]."_".$DesUser["id_user"])).'"/>';
        $ret.= '            <textarea name="description" placeholder="'.RTTCore::l('Type your message','admin') .' " required></textarea>';
        $ret.= '            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/1940306/ico_picture.png" alt="">';
        $ret.= '            <img src="https://s3-us-west-2.amazonaws.com/s.cdpn.io/1940306/ico_file.png" alt="">';
        $ret.= '            <button name="btnSubmitAdd" type="submit">'.RTTCore::l('Send','admin') .'</button>';
        $ret.= '        </form></footer>';
        $ret.= '    </main>';
        $ret.= '</div>';
					    
        return $ret;
    }
}
?>