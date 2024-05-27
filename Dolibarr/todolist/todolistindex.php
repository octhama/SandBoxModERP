<?php
/* Copyright (C) 2001-2005 Rodolphe Quiedeville <rodolphe@quiedeville.org>
 * Copyright (C) 2004-2015 Laurent Destailleur  <eldy@users.sourceforge.net>
 * Copyright (C) 2005-2012 Regis Houssin        <regis.houssin@inodbox.com>
 * Copyright (C) 2015      Jean-François Ferry	<jfefe@aternatik.fr>
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <https://www.gnu.org/licenses/>.
 */

/**
 *	\file       todolist/todolistindex.php
 *	\ingroup    todolist
 *	\brief      Home page of todolist top menu
 */

// Load Dolibarr environment
$res = 0;
// Try main.inc.php into web root known defined into CONTEXT_DOCUMENT_ROOT (not always defined)
if (!$res && !empty($_SERVER["CONTEXT_DOCUMENT_ROOT"])) {
	$res = @include $_SERVER["CONTEXT_DOCUMENT_ROOT"]."/main.inc.php";
}
// Try main.inc.php into web root detected using web root calculated from SCRIPT_FILENAME
$tmp = empty($_SERVER['SCRIPT_FILENAME']) ? '' : $_SERVER['SCRIPT_FILENAME']; $tmp2 = realpath(__FILE__); $i = strlen($tmp) - 1; $j = strlen($tmp2) - 1;
while ($i > 0 && $j > 0 && isset($tmp[$i]) && isset($tmp2[$j]) && $tmp[$i] == $tmp2[$j]) {
	$i--;
	$j--;
}
if (!$res && $i > 0 && file_exists(substr($tmp, 0, ($i + 1))."/main.inc.php")) {
	$res = @include substr($tmp, 0, ($i + 1))."/main.inc.php";
}
if (!$res && $i > 0 && file_exists(dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php")) {
	$res = @include dirname(substr($tmp, 0, ($i + 1)))."/main.inc.php";
}
// Try main.inc.php using relative path
if (!$res && file_exists("../main.inc.php")) {
	$res = @include "../main.inc.php";
}
if (!$res && file_exists("../../main.inc.php")) {
	$res = @include "../../main.inc.php";
}
if (!$res && file_exists("../../../main.inc.php")) {
	$res = @include "../../../main.inc.php";
}
if (!$res) {
	die("Include of main fails");
}

require_once DOL_DOCUMENT_ROOT.'/core/class/html.formfile.class.php';

// Load translation files required by the page
$langs->loadLangs(array("todolist@todolist"));

$action = GETPOST('action', 'aZ09');

$max = 5;
$now = dol_now();

// Security check - Protection if external user
$socid = GETPOST('socid', 'int');
if (isset($user->socid) && $user->socid > 0) {
	$action = '';
	$socid = $user->socid;
}

// Security check (enable the most restrictive one)
//if ($user->socid > 0) accessforbidden();
//if ($user->socid > 0) $socid = $user->socid;
//if (!isModEnabled('todolist')) {
//	accessforbidden('Module not enabled');
//}
//if (! $user->hasRight('todolist', 'myobject', 'read')) {
//	accessforbidden();
//}
//restrictedArea($user, 'todolist', 0, 'todolist_myobject', 'myobject', '', 'rowid');
//if (empty($user->admin)) {
//	accessforbidden('Must be admin');
//}


/*
 * Actions
 */

// None


/*
 * View
 */


llxHeader("", $langs->trans("TodolistArea"), '', '', 0, 0, '', '', '', 'mod-todolist page-index');

echo '<!DOCTYPE html>';
echo '<html lang="fr">';
echo '<head>';
echo '<meta charset="UTF-8">';
echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">';
echo '<title>Liste des tâches</title>';
echo '<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css">';
echo '<link rel="stylesheet" href="/css/todolist.css.php">';
echo '</head>';
echo '<body>';
echo '<div class="container">';
echo '<div class="container">';
echo '<h1><img src="/img/object_todolist.png" alt="Module Icon" class="module-icon">Module Dolibarr</h1>';
echo '</div>';
echo '<div class="container">';
echo '<h1>Liste des tâches</h1>';
echo '<form action="todolistadd.php" method="post" class="mb-3">';
echo '<div class="input-group">';
echo '<label for="newTask"></label>';
echo '<input type="text" id="newTask" name="newTask" class="form-control" placeholder="Nouvelle tâche" required>';
echo '<label for="newDate"></label>';
echo '<input type="date" id="newDate" name="newDate" class="form-control" required>';
echo '<label for="newTime"></label>';
echo '<input type="time" id="newTime" name="newTime" class="form-control" required>';
echo '<button type="submit" class="btn btn-primary" id="liveToastBtn">Ajouter</button>';
echo '</div>';
echo '<div class="toast-container position-fixed bottom-0 end-0 p-3">';
echo '<div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">';
echo '<div class="toast-header">';
echo '<strong class="me-auto">Notification</strong>';
echo '<button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>';
echo '</div>';
echo '<div class="toast-body" id="toastBody">';
echo 'Tâche ajoutée !';
echo '</div>';
echo '</div>';
echo '</div>';
echo '</form>';
echo '</div>';

if (!empty($todos)) {
    if (count($todos) > 0) {
        // List of tasks
        echo '<table class="table table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th scope="col">Tâche</th>';
        echo '<th scope="col">Date</th>';
        echo '<th scope="col">Heure</th>';
        echo '<th scope="col">Actions</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        foreach ($todos as $todo) {
            echo '<tr>';
            echo '<td>'.htmlspecialchars($todo['task']).'</td>';
            echo '<td>'.htmlspecialchars($todo['date']).'</td>';
            echo '<td>'.htmlspecialchars($todo['time']).'</td>';
            echo '<td>';
            echo '<a href="../todolistedit.php?id='.urlencode($todo['id']).'" class="btn btn-warning">Modifier</a>';
            echo '<a href="../todolistdelete.php?id='.urlencode($todo['id']).'" class="btn btn-danger">Supprimer</a>';
            echo '</td>';
            echo '</tr>';
            echo '</tbody>';
            echo '</table>';
        }
    }
} else {
    echo '<p class="alert alert-info">Aucune tâche n\'est présente dans la liste.</p>';
}
echo '</div>';
echo '<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>';
echo '</body>';
echo '</html>';



// End of page
llxFooter();

