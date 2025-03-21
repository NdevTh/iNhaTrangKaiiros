<!-- <?php require("/Users/DevInfo/OneDrive/LPDAOO/Code/fosnotBack/bdd/bdd.php");

        ?> -->

<div class="coursBack">
    <a href="index.php?page=addEvent"><button>Nouveau</button> </a>
    <table>
        <tr>
            <th>Modifier</th>
            <th>Supprimer</th>
            <th>Id</th>
            <th>Titres</th>
            <th>Description</th>
            <th>Images</th>
        </tr>
        <?php
        $req = $db->query('SELECT * from events;');
        $data = $req->fetchAll();

        foreach ($data as $events) {
            echo "<tr>
            <td><a href='index.php?page=eventsForm&id=" . $events['eve_id'] . "'><button>Modifier</button> </a></td>
            <td><a href='index.php?page=deleteEvent&id=" . $events['eve_id'] . "'><button>Supprimer</button> </a></td>
            <td>" . $events['eve_id'] . "</td>
            <td>" . $events['eve_title'] . "</td>
            <td>" . $events['eve_description'] . "</td>
            <td>" . $events['eve_directory'] . "</td>
            </tr>";
        }
        ?>
    </table>
</div>