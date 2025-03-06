<?php
use Bundles\classes\AdminTab as AdminTab;
use Bundles\classes\XMLFile as  XMLFile;
use Bundles\classes\Tools as  Tools;
use Bundles\classes\WebCore as  Core;
use Bundles\classes\myUser as myUser;
use Bundles\classes\myCompany as myCompany;
use Bundles\classes\xUserGroup as xUserGroup;
use Bundles\classes\xSystemRole as xSystemRole;

class User extends AdminTab
{
    public static function form()
    {
        $ret = '';
        $btnType = 'Add';
        $record = array();
        $currentDate = date('Y-m-d h:i:s', time());
        
        if (Tools::getValue('id')){
            //$record = myCompany::getById(Tools::getValue('id'))[0];
            $btnType = 'Edit';
            $record = myUser::getById(Tools::getValue('id'))[0];
            //var_dump($record);
        }
        if (Tools::isSubmit('btnSubmitAdd')){
            if ($_POST["password"] == $_POST["password"]){
                $ret.= '<div class="msg">';
                $ret.= '   Action "Add" was successful';
                $ret.= '</div>';
                $_POST["date_add"] = $currentDate;
                $_POST["date_upd"] = $currentDate;
                $_POST["password"] = md5($_POST["password"]);
                $insert_id = myUser::save();
                $record = myUser::getById($insert_id)[0];
            }else {
                $ret.= '<div class="msg">';
                $ret.= '   Action "Add" wasnot successful, password not match!';
                $ret.= '</div>';
            }
        }
        
        if (Tools::isSubmit('btnSubmitEdit')){

            if (isset($_POST["midification"])){
                $_POST["password"] = md5($_POST["password"]);
            }
            $_POST["date_upd"] = $currentDate;
            myUser::updateById(Tools::getValue('id'));
            $record = myUser::getById(Tools::getValue('id'))[0];
            $ret.= '<div class="msg">';
            $ret.= '    Action "Edit" was successful';
            $ret.= '</div>';
        }
        //$ret.= 'Url:' . $_SERVER["REQUEST_URI"];
        //$ret.= 'new Url: ' .Tools::newUrl('t','tab');
        $ret.= '<form action="core.php?'.Tools::newUrl('t','user').'" method="POST" class="form">';
        $ret.= '  <fieldset class="edge-rond">';
        $ret.= '    <legend class="">User Form</legend>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>Nom et Prénom * </label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="full_name" type="text" value="'.(isset($record)?$record['full_name']:"").'" required/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>Adresse e-mail * </label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="email" type="text" value="'.(isset($record)?$record['email']:"").'" required/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>Mot de passe * </label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="password" type="password" value="'.(isset($record)?$record['password']:"").'" required/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <input name="midification" type="checkbox" value="1" '.$chkCheck.'/> ';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <label>Modification </label> ';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>Confirmer le mot de passe * </label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="confirmpassword" type="password" value="'.(isset($record)?$record['password']:"").'" required/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>Code ou VISA</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <input name="code" type="text" value="'.(isset($record)?$record['code']:"").'"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        /*
        $companies = array(
          array('id_company'=>'1','company'=>'Flashlab-Illkirch','company_type'=>'customer'),
          array('id_company'=>'2','company'=>'Flashlab-Environnement','company_type'=>'customer'),
          array('id_company'=>'3','company'=>'Flashlab-Amiante','company_type'=>'customer'),
          array('id_company'=>'4','company'=>'Flashlab-Lezzenne','company_type'=>'customer'),
          array('id_company'=>'5','company'=>'GimoPharm','company_type'=>'customer'),
          array('id_company'=>'6','company'=>'GimoExpert','company_type'=>'owner')
        );*/
        $companies = myCompany::getRecords();
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>Entreprise</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <select name="id_company">';
        $ret.= '          <option value="0"> Select option </option>';
        //$gRecords = XMLFile::readFile('tabs','','xml');
        if (!empty($companies)){
            foreach($companies as $companie)
            {
                if ($companie['id_company'] == $record['company']){
                    $ret.= '<option value="'.$companie["id_company"].'" selected> '.$companie["id_company"].' '.$companie["company"].' </option>';
                }else{
                    $ret.= '<option value="'.$companie["id_company"].'" > '.$companie["id_company"].' '.$companie["company"].' </option>';
                }
            }
        }
        $ret.= '          </select>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $usergroups = xUserGroup::getRecords();
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>Group User</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <select name="user_group">';
        $ret.= '          <option value="0"> Select option </option>';
        //$gRecords = XMLFile::readFile('tabs','','xml');
        if (!empty($usergroups)){
            foreach($usergroups as $usergroup)
            {
                if ($usergroup['id'] == $record['user_group']){
                    $ret.= '<option value="'.$usergroup["id"].'" selected> '.$usergroup["id"].' - '.$usergroup["name"].' </option>';
                }else{
                    $ret.= '<option value="'.$usergroup["id"].'" > '.$usergroup["id"].' - '.$usergroup["name"].' </option>';
                }
            }
        }
        $ret.= '          </select>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $systemroles = xSystemRole::getRecords();
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>System Role</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $ret.= '          <select name="system_role">';
        $ret.= '          <option value="0"> Select option </option>';
        //$gRecords = XMLFile::readFile('tabs','','xml');
        if (!empty($systemroles)){
            foreach($systemroles as $systemrole)
            {
                if ($systemrole['id'] == $record['system_role'] ){
                    $ret.= '<option value="'.$systemrole["id"].'" selected> ' .$systemrole['id'].' - '.$systemrole["name"].' </option>';
                }else {
                    $ret.= '<option value="'.$systemrole["id"].'" > '.$systemrole['id'].' - '.$systemrole["name"].' </option>';
                }
            }
        }
        $ret.= '          </select>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '    <div class="row">';
        $ret.= '       <div class="groupform">';
        $ret.= '          <label>Active</label>';
        $ret.= '       </div>';
        $ret.= '       <div class="groupfield">';
        $active = '';
        if (Tools::getValue('active') == '1' OR $record['active'] == 'on' OR $record['active'] == '1')
        {
            $active = 'checked';
        }
        $ret.= '          <input name="active" '.$active.' type="checkbox" value="1"/>';
        $ret.= '       </div>';
        $ret.= '    </div>';
        
        $ret.= '<div class="row">';
        $ret.= '   <div class="col-6 txt-left">';
        //$ret.= '     <a class="btn" href="index.php?t='.Tools::getValue('pt').'&act=form"><i class="bx bx-arrow-to-left"></i>Retourner</a>';
        $ret.= '   </div>';
        $ret.= '   <div class="col-6 txt-right">';
        $ret.= '      <a href="core.php?t='.Tools::getValue('t').'" class="btn-form"><i class="bx bx-error-circle" ></i> Annuler</a>';
        $ret.= '      <button name="btnSubmit'.$btnType.'" class="btn-form" type="submit">'.$btnType.'</button>';
        $ret.= '   </div>';
        $ret.= '</div>';
        $ret.= '  </fieldset>';
        $ret.= '</form>';
        return $ret;
    }
    public static function viewUser()
    {
        $ret = "";
        $records = array();
        $fieldsDisplay = array(
           'id_user'             => 'ID',
           'full_name'           => 'NOM',
           'email'               => 'ADDRESSE E-MAIL',
           //'name'                => 'NOM',
           'code'                => 'CODE PERSONNEL',
           'user_group'      => 'INTITULÉ DE POSTE',
           'system_role'         => 'SYSTÈME RÔLE',
           'active'              => 'STATUT'
           //'serial_number'       => 'Numérie de série'
        );
        $records = myUser::getRecords();
        /*
        $ret.= '<div class="row tools-title">';
        $ret.= '   <div class="col-2 txt-left">';
        $ret.= '     <a class="btn" href="index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').'&act=form"><i class="bx bx-arrow-to-left"></i>Retourner</a>';
        $ret.= '   </div>';
        $ret.= '   <div class="col-2 txt-right">';
        $ret.= '     <a class="btn" href="index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').'&act=form'.'&token='.Tools::getValue('token').'"> XLS</a>';
        $ret.= '     <a class="btn" onclick="return printDoc(\'myTable\');" href=""> <i class="bx bx-printer"></i> Imprimer</a>';
        $ret.= '     <a class="btn" href="index.php?t='.Tools::getValue('t').'&pt='.Tools::getValue('pt').'&act=form'.'&token='.Tools::getValue('token').'"> <i class="bx bx-plus-circle"></i> Créer</a>';
        $ret.= '   </div>';
        $ret.= '</div>';
        */
        $ret.= '<div class="row">';
        $ret.= '  <a class="" href="core.php?t='.Tools::getValue('t').'&act=form"><img class="tbl-ico32" src="images/advancetools/1828819.png"/></a>';
        $ret.= '</div>';
        
        $ret.= '<div class="row">';
        $ret.= '<div class="bg-table">';
        $ret.= '  <div class="row tools-title">';
        $ret.= '     <span class="tbl-title">Utilisateurs</span>';
        $ret.= '     <span class="article">'.count($records).' Article(s)</span>';
        $ret.= '     <input id="inpSearch" onkeyup="myFunction(this)" class="txt-search flt-right" type="text" placeholder="Introduir le mot clé pour rechercher" />';
        $ret.= '  </div>';
        $ret.= '  <div class="row table">';
        $ret.= '     <table id="myTable">';
        $ret.= '<tr>';
        $indCol = 0;
        foreach($fieldsDisplay as $k => $th)
        {
            $indCol++;
            $ret.= '<th onclick="sortTable('.$indCol.')" class="bg-orange"> <img class="tbl-ico" id="col'.$indCol.'" src="images/advancetools/4340090.png"/> &nbsp; '.strtoupper($th).'</th>';
        }
        $ret.= '<th class="bg-orange">Action</th>';
        $ret.= '</tr>';
        
        /* body table */
        if ($records){
            foreach($records as $record)
            {
                $ret.= '<tr>';
                foreach($fieldsDisplay as $field => $th)
                {
                    /*
                    if ($field != 'complex_site')
                    {
                        $ret.= '<td>'.$record[$field].'</td>';
                    }else{
                        $ret.= '<td><a href="index.php?t=complex&pt='.Tools::getValue('pt').'&act=list&cid='.$record["id_user"].'" >'.$record[$field].'</a></td>';
                    }*/
                    if ($field == 'active' && $record[$field] == 1)
                    {
                        $ret.= '<td class="txt-center">Active</td>';
                    } elseif ($field == 'active' && $record[$field] != 1) {
                        $ret.= '<td class="txt-center">Déactive</td>';
                    }
                    elseif ($field == 'user_group')
                    {
                        $usergroup = xUserGroup::getById($record[$field]);
                        $ret.= '<td class="txt-center">'.$usergroup['name'].'</td>';
                    }
                    elseif ($field == 'system_role')
                    {
                        $systemrole = xSystemRole::getById($record[$field]);
                        $ret.= '<td class="txt-center">'.$systemrole['name'].'</td>';
                    }elseif ($field == 'id_user'){
                        $ret.= '<td class="txt-center">'.$record[$field].'</td>';
                    }else{
                        $ret.= '<td class="">'.$record[$field].'</td>';
                    }
                }
                $ret.= '<td class="txt-center">';
                $ret.= '  <a href="index.php?t='.Tools::getValue('t').'&act=form&id='.$record["id_user"].'&token='.Tools::getValue('token').'"><img class="tbl-ico32" src="Bundles/images/advancetools/10629723.png"/></a>';
                $ret.= '  <a href="index.php?t='.Tools::getValue('t').'&act=del&id='.$record["id_user"].'&token='.Tools::getValue('token').'"><img class="tbl-ico32" src="Bundles/images/advancetools/10871842.png"/></a>';
                $ret.= '</td>';
                $ret.= '</tr>';
            }
        }
        $ret.= '</table>';
        $ret.= '</div>';
        $ret.= '</div>';
        $ret.= '   </div>';
        return $ret;
    }
}
?>