<?php
/*
 * @version $Id$
 -------------------------------------------------------------------------
 GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2015 Teclib'.

 http://glpi-project.org

 based on GLPI - Gestionnaire Libre de Parc Informatique
 Copyright (C) 2003-2014 by the INDEPNET Development Team.
 
 -------------------------------------------------------------------------

 LICENSE

 This file is part of GLPI.

 GLPI is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 GLPI is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with GLPI. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */

/** @file
* @brief
*/

include ('../inc/includes.php');

// Change profile system
if (isset($_POST['newprofile'])) {
   if (isset($_SESSION["glpiprofiles"][$_POST['newprofile']])) {
      Session::changeProfile($_POST['newprofile']);

      if ($_SESSION["glpiactiveprofile"]["interface"] == "central") {
         Html::redirect($CFG_GLPI['root_doc']."/front/central.php");
      } else {
         Html::redirect($_SERVER['PHP_SELF']);
      }

   } else {
      Html::redirect(preg_replace("/entities_id=.*/","",$_SERVER['HTTP_REFERER']));
   }
}

// Manage entity change
if (isset($_GET["active_entity"])) {
   if (!isset($_GET["is_recursive"])) {
      $_GET["is_recursive"] = 0;
   }
   if (Session::changeActiveEntities($_GET["active_entity"],$_GET["is_recursive"])) {
      if ($_GET["active_entity"] == $_SESSION["glpiactive_entity"]) {
         Html::redirect(preg_replace("/entities_id.*/","",$_SERVER['HTTP_REFERER']));
      }
   }
}

// Redirect management
if (isset($_GET["redirect"])) {
   Toolbox::manageRedirect($_GET["redirect"]);
}

// redirect if no create ticket right
if (!Session::haveRight('ticket', CREATE)
    && !Session::haveRight('reminder_public', READ)
    && !Session::haveRight("rssfeed_public", READ)) {

   if (Session::haveRight('followup', TicketFollowup::SEEPUBLIC)
       || Session::haveRight('task', TicketTask::SEEPUBLIC)
       || Session::haveRightsOr('ticketvalidation', array(TicketValidation::VALIDATEREQUEST,
                                                          TicketValidation::VALIDATEINCIDENT))) {
      Html::redirect($CFG_GLPI['root_doc']."/front/ticket.php");

   } else if (Session::haveRight('reservation', ReservationItem::RESERVEANITEM)) {
      Html::redirect($CFG_GLPI['root_doc']."/front/reservationitem.php");

   } else if (Session::haveRight('knowbase', KnowbaseItem::READFAQ)) {
      Html::redirect($CFG_GLPI['root_doc']."/front/helpdesk.faq.php");
   }
}

Session::checkHelpdeskAccess();

?>

<style>

#page .central tr td a:before {
    content: "";
    width: 16px;
    display: inline-block;
    background: center no-repeat;
    height: 12px;
    vertical-align: middle;
    margin-bottom: 2px;
    margin-right: 5px;
}

#page .central table:nth-child(1) tr:nth-child(3) td a:before {
   background-image: url(../../ti/pics/new.png);
}

#page .central table:nth-child(1) tr:nth-child(4) td a:before {
   background-image: url(../../ti/pics/assign.png);
}

#page .central table:nth-child(1) tr:nth-child(5) td a:before {
   background-image: url(../../ti/pics/plan.png);
}

#page .central table:nth-child(1) tr:nth-child(6) td a:before {
   background-image: url(../../ti/pics/waiting.png);
}

#page .central table:nth-child(1) tr:nth-child(7) td a:before {
   background-image: url(../../ti/pics/solved.png);
}

#page .central table:nth-child(1) tr:nth-child(8) td a:before {
   background-image: url(../../ti/pics/closed.png);
}

#page .central table:nth-child(1) tr:nth-child(9) td a:before {
    background-color: #d22d2d;
    width: 12px;
    margin-right: 7px;
    margin-left: 2px;
    border-radius: 100%;
}

.tab_bg_1 .select2-container {
    display: none;
}


</style>

<?php


if (isset($_GET['create_ticket'])) {

  $con = mysqli_connect("10.1.10.198","wsdl","trGSTkm1woUyUHS1", "glpi");
  if (!$con) {
    $status =  mysqli_connect_error();
  }

  // if($con && $db) $status = "Tudo certo!";
  mysqli_set_charset($con,"utf8");
  mysqli_query("SET NAMES 'utf8'");
  mysqli_query('SET character_set_connection=utf8');
  mysqli_query('SET character_set_client=utf8');
  mysqli_query('SET character_set_results=utf8');

   $sqlUserLocation = "SELECT * FROM glpi_users WHERE id = ".Session::getLoginUserID();
   $resUserLocation = mysqli_query($con, $sqlUserLocation);
   $rowUserLocation = mysqli_fetch_array($resUserLocation);

   //echo "AQUI: ".$rowUserLocation['locations_id'];

   ?>

   <script>

     var elemento = document.getElementsByName('locations_id');
     setTimeout(function(){ elemento[0].value = "<?=$rowUserLocation['locations_id'];?>"; }, 3000);

     
     //elemento[0].style.color = "#CCC";


   </script>

   <?php



   Html::helpHeader(__('New ticket'), $_SERVER['PHP_SELF'], $_SESSION["glpiname"]);
   $ticket = new Ticket();
   $ticket->showFormHelpdesk(Session::getLoginUserID());

} else {
   Html::helpHeader(__('Home'), $_SERVER['PHP_SELF'], $_SESSION["glpiname"]);
   echo "<table class='tab_cadre_postonly'><tr class='noHover'>";
   echo "<td class='top' width='50%'><br>";
   echo "<table class='central'>";
   if (Session::haveRight('ticket', CREATE)) {
      echo "<tr class='noHover'><td class='top'>";
      Ticket::showCentralCount(true);
      echo "</td></tr>";
      echo "<tr class='noHover'><td class='top'>";
      Ticket::showCentralList(0, "survey", false);
      echo "</td></tr>";
   }

   
   
   if (Session::haveRight("reminder_public", READ)) {
      echo "<tr class='noHover'><td class='top'>";
      Reminder::showListForCentral(false);
      echo "</td></tr>";
   }

   if (Session::haveRight("rssfeed_public", READ)) {
      echo "<tr class='noHover'><td class='top'>";
      RSSFeed::showListForCentral(false);
      echo "</td></tr>";
   }
   echo "</table></td>";

   echo "<td class='top' width='50%'><br>";
   echo "<table class='central'>";

   // Show KB items
   if (Session::haveRight('knowbase', KnowbaseItem::READFAQ)) {
      echo "<tr class='noHover'><td class='top'>";
      KnowbaseItem::showRecentPopular("popular");
      echo "</td></tr>";
      echo "<tr class='noHover'><td class='top'><br>";
      KnowbaseItem::showRecentPopular("recent");
      echo "</td></tr>";
      echo "<tr class='noHover'><td class='top'><br>";
      KnowbaseItem::showRecentPopular("lastupdate");
      echo "</td></tr>";
   } else {
      echo "<tr><td>&nbsp;</td></tr>";
   }

   echo "</table>";
   echo "</td>";
   echo "</tr></table>";

}

Html::helpFooter();
?>