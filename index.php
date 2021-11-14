<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Excel to Database</title>
    <style>
        * {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
            font-family: Cairo;
        }

        body {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            width: 100%;
            height: 100vh;
        }

        form {
            border: 3px solid;
        }

        form div {
            text-align: center;
            margin: 20px;
            /* border: 5px red solid; */
        }

        form div p {
            font-weight: 800;
            font-family: Cairo;
        }

        form div input {
            width: 100%;
        }
    </style>
</head>

<body>
    <form method="POST" enctype="multipart/form-data">
        <div>
            <p>Main File : </p>
            <input type="file" name="doc1" />
        </div>
        <div>
            <p>Text File : </p>
            <input type="file" name="doc2" />
        </div>
        <div>
            <input type="submit" name="compare" value="Compare" />
        </div>
    </form>

    <?php

    class Product
    {
    }


    $mainArray = array();
    $textArray = array();

    if (isset($_POST['compare'])) {

        $ext1 = pathinfo($_FILES['doc1']['name'], PATHINFO_EXTENSION);
        $ext2 = pathinfo($_FILES['doc2']['name'], PATHINFO_EXTENSION);

        if ($ext1 == "xlsx" && $ext2 == "xlsx") {

            require('PHPExcel/PHPExcel.php');
            require('PHPExcel/PHPExcel/IOFactory.php');

            $file1 = $_FILES['doc1']['tmp_name'];
            $file2 = $_FILES['doc2']['tmp_name'];

            $obj1 = PHPExcel_IOFactory::load($file1);
            $obj2 = PHPExcel_IOFactory::load($file2);

            foreach ($obj1->getWorksheetIterator() as $sheet1) {

                $getHighestRow1 = $sheet1->getHighestRow();
                // $getHighestColumn1 = $sheet1->getHighestColumn();
                // Sir ye $getHighestColumn1 se 'C' get ho raha usi ko int me convert karna hai

                for ($i = 1; $i <= $getHighestRow1; $i++) {
                    $main = new Product();
                    for ($j = 'A'; $j < $getHighestColumn1; $j++) {
                        echo $j . "<br/>";
                        $mainData = $sheet1->getCellByColumnAndRow($j, $i)->getValue();
                        $main->$j = $mainData;
                    }
                    // $id1 = $sheet1->getCellByColumnAndRow(0, $i)->getValue();
                    // $name1 = $sheet1->getCellByColumnAndRow(1, $i)->getValue();
                    // $price1 = $sheet1->getCellByColumnAndRow(2, $i)->getValue();

                    // $main->id = $id1;
                    // $main->name = $name1;
                    // $main->price = $price1;

                    // array_push($mainArray, $main);
                }
            }

            // foreach ($obj2->getWorksheetIterator() as $sheet2) {

            //     $getHighestRow2 = $sheet2->getHighestRow();
            // $getHighestColumn2 = $sheet2->getHighestColumn();

            //     for ($i = 1; $i <= $getHighestRow2; $i++) {
            //         $text = new Product();
            //         for ($j = 'A'; $j < $getHighestColumn2; $j++) {
            //             $textData = $sheet2->getCellByColumnAndRow($j, $i)->getValue();
            //             $text->$j = $textData;
            //         }

            //         $id2 = $sheet2->getCellByColumnAndRow(0, $i)->getValue();
            //         $name2 = $sheet2->getCellByColumnAndRow(1, $i)->getValue();
            //         $price2 = $sheet2->getCellByColumnAndRow(2, $i)->getValue();

            //         $text->id = $id2;
            //         $text->name = $name2;
            //         $text->price = $price2;

            //         array_push($textArray, $text);
            //     }
            // }
        } else {
            echo "Invalid File Format";
        }
        $result = array_merge($mainArray, $textArray);
        //duplicate objects will be removed
        $result = array_map("unserialize", array_unique(array_map("serialize", $result)));
        //array is sorted on the bases of id
        sort($result);

        echo "<br/>";
        foreach ($result as $childArray) {
            print_r($childArray);
            echo "<br/><br/>";
        }
    }
    ?>

</body>

</html>