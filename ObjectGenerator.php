public function getTables() {
    
    $query= "SHOW TABLES";
    
    $stmt = $this->conn->prepare($query);
    $stmt->execute();

    $res = $stmt->fetchAll();

    $table_names = [];

    $x = "";

    $count = 0;

    foreach($res as $table) {

        $count ++;
        $x = "";

        $name_of_table = $table[0];
        $count = count($table_names);
        $table_names[$count]["name"] = $name_of_table;

        $x = $x . "<?php \n\nclass $name_of_table"."Object{\n\n";

        $getters = "";
        $setters = "";

        $query_tables = 'select COLUMN_NAME from INFORMATION_SCHEMA.COLUMNS where TABLE_NAME= :tableName ';
        $result  = $this->SELECT($query_tables,[":tableName" => $name_of_table],true,false);
        $count_columns = 0;
        foreach ($result as $key => $column_name) {



            $count_columns  = count($table_names[$count]["columns"]);
            $table_names[$count]["columns"][$count_columns] = $column_name["COLUMN_NAME"];

            $col_name = $column_name["COLUMN_NAME"];

            $x = $x . "\npublic string $".$col_name.'="";';

            $getters = $getters . "\n\npublic function get$col_name(): string {\n";
            $getters = $getters . "\n\treturn $"."this->$col_name;\n}\n";

            $setters = $setters . "\n\npublic function set$col_name(string $"."var_$col_name): void \n{\n";
            $setters = $setters . "\n\t$"."this->$col_name =$"."var_$col_name ;\n}\n";

        }

        $x = "$x $getters $setters \n}\n\n?>";

        //if ($count < 3) {
            file_put_contents(TEMP.$name_of_table."Object.php",$x);
        //}


    }

    //file_put_contents(TEMP."tables",$x);
    //file_put_contents(TEMP."tables",$x);

}
