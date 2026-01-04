<?php
class Form {
    private $fields = [];

    public function add($label, $name, $type="text") {
        $this->fields[] = compact('label', 'name', 'type');
    }

    public function display($action) {
        echo "<form method='POST' action='$action'>";
        echo "<table>";

        foreach ($this->fields as $f) {
            echo "<tr>
                    <td>{$f['label']}</td>
                    <td><input type='{$f['type']}' name='{$f['name']}'></td>
                  </tr>";
        }

        echo "<tr><td colspan='2'><button type='submit'>Simpan</button></td></tr>";
        echo "</table>";
        echo "</form>";
    }
}
