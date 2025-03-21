<?php require("/Users/DevInfo/OneDrive/LPDAOO/Code/fosnotBack/bdd/bdd.php");
?>

<div class="coursBack">
    <table>
        <tr>
            <th>Modifier</th>
            <th>Id</th>
            <th>Titres</th>
            <th>Description</th>
            <th>Images</th>
        </tr>
        <?php
        $req = $db->query('SELECT * from education;');
        $data = $req->fetchAll();

        foreach ($data as $cours) {
            echo "<tr>
            <td><a href='index.php?page=coursForm&id=" . $cours['edu_id'] . "'><button>Modifier</button> </a></td>
            <td>" . $cours['edu_id'] . "</td>
            <td>" . $cours['edu_title'] . "</td>
            <td>" . $cours['edu_description'] . "</td>
            <td>" . $cours['edu_directory'] . "</td>
            </tr>";
        }
        ?>
    </table>
</div>