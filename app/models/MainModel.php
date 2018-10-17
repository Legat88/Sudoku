<?php
/**
 * Created by PhpStorm.
 * User: legat
 * Date: 10.10.2018
 * Time: 14:47
 */
require_once 'ArrayModel.php';

class MainModel extends Model
{
    private static $func_counter = 0;

    public function putFileToArray($file)
    {
        $array = file($file, FILE_IGNORE_NEW_LINES); //Разбиваем файл на строки, без символа перевода строки в конце
        for ($i = 0; $i < count($array); $i++) {
            $array[$i] = explode(' ', $array[$i]);
        }
        return $array;
    }

    /** Аналог функции array_intersect(), но многократно быстрее при условии отсортированных заранее массивов
     * @param $a1 - первый массив
     * @param $a2 - второй массив
     * @return array - схождение массивов
     */
    private function intersect(&$a1, &$a2)
    {
        if ($a1 === $a2) return ($a1);
        $a3 = array();
        $b2 = current($a2);
        if ($b2 !== false) foreach ($a1 as $b1) {
            while ($b1 > $b2) {
                $b2 = next($a2);
                if ($b2 === false) break(2);
            }
            if ($b2 == $b1) $a3[] = $b1;
        }
        return $a3;
    }

    public function analyzeArrays($array)
    {
        $array_model = new ArrayModel();
        $hdiff = $array_model->makeHorizontalArray($array);
        $new_vdiff = $array_model->makeVerticalArray($array);
        $new_square = $array_model->makeSquareArray($array);

        //Сравниваем массивы попарно
        //Сравниваем первую пару
        for ($i = 0; $i < 9; $i++) {
            for ($j = 0; $j < 9; $j++) {
                $hdiff[$i][$j] = $this->intersect($hdiff[$i][$j], $new_vdiff[$i][$j]);
//                $hdiff[$i][$j] = array_intersect($hdiff[$i][$j], $new_vdiff[$i][$j], $new_square[$i][$j]);
            }
        }

        //Удаляем пустые значения
        $hdiff = $array_model->removeNullCells($hdiff);

        //Сравниваем вторую пару
        for ($i = 0; $i < 9; $i++) {
            for ($j = 0; $j < 9; $j++) {
                $hdiff[$i][$j] = $this->intersect($hdiff[$i][$j], $new_square[$i][$j]);
//                $hdiff[$i][$j] = array_intersect($hdiff[$i][$j], $new_square[$i][$j]);
            }
        }
        //Удаляем пустые значения
        $hdiff = $array_model->removeNullCells($hdiff);

        ksort($hdiff);
        $duples_hor = null;

        //Находим дупликаты в горизонтальном направлении
        for ($i = 0; $i < 9; $i++) {
            $m = 0;
            for ($j = 0; $j < 9; $j++) {
                $m++;
                $hit_counter = 1;
                $compares_keys_x = null;
                for ($k = $m; $k < 9; $k++) {
                    if (count($hdiff[$i][$j]) > 0 && count($hdiff[$i][$j]) == count($hdiff[$i][$k])) {
                        $differ = array_diff($hdiff[$i][$j], $hdiff[$i][$k]);
                        if (count($differ) == 0 && $differ !== null) {
                            $hit_counter++;
                            $duples_hor[$i][$j] = $hdiff[$i][$j];
                            $duples_hor[$i][$k] = $hdiff[$i][$k];
                            $compares_keys_x[] = $k;
                        }
                    }
                }
                if ($hit_counter == 2 && count($hdiff[$i][$j]) == 2) {
                    foreach (array_values($hdiff[$i][$j]) as $value) {
                        foreach (array_keys($hdiff[$i]) as $key1) {
                            if ($key1 != $j && $key1 != $compares_keys_x[0]) {
                                foreach (array_keys($hdiff[$i][$key1]) as $key2) {
                                    if ($hdiff[$i][$key1][$key2] == $value) {
                                        unset($hdiff[$i][$key1][$key2]);
                                    }
                                }
                            }
                        }
                    }
                }
                if ($hit_counter == 3 && count($hdiff[$i][$j]) == 3) {
                    foreach (array_values($hdiff[$i][$j]) as $value) {
                        foreach (array_keys($hdiff[$i]) as $key1) {
                            if ($key1 != $j && $key1 != $compares_keys_x[0] && $key1 != $compares_keys_x[1]) {
                                foreach (array_keys($hdiff[$i][$key1]) as $key2) {
                                    if ($hdiff[$i][$key1][$key2] == $value) {
                                        unset($hdiff[$i][$key1][$key2]);
                                    }
                                }
                            }
                        }
                    }
                }
                if ($hit_counter == 4 && count($hdiff[$i][$j]) == 4) {
                    foreach (array_values($hdiff[$i][$j]) as $value) {
                        foreach (array_keys($hdiff[$i]) as $key1) {
                            if ($key1 != $j && $key1 != $compares_keys_x[0] && $key1 != $compares_keys_x[1] && $key1 != $compares_keys_x[2]) {
                                foreach (array_keys($hdiff[$i][$key1]) as $key2) {
                                    if ($hdiff[$i][$key1][$key2] == $value) {
                                        unset($hdiff[$i][$key1][$key2]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        //Удаляем пустые значения
        $duples_hor = $array_model->removeNullCells($duples_hor);

        ksort($hdiff);
        $duples_vert = null;

        //Находим дупликаты в вертикальном направлении
        $m = 0;
        for ($i = 0; $i < 9; $i++) {
            $m++;
            for ($j = 0; $j < 9; $j++) {
                $k = 0;
                $hit_counter = 1;
                $compares_keys_y = null;
                for ($k = $m; $k < 9; $k++) {
                    if (count($hdiff[$i][$j]) > 0 && count($hdiff[$i][$j]) == count($hdiff[$k][$j])) {
                        $differ = array_diff($hdiff[$i][$j], $hdiff[$k][$j]);
                        if (count($differ) == 0 && $differ !== null) {
                            $hit_counter++;
                            $duples_vert[$i][$j] = $hdiff[$i][$j];
                            $duples_vert[$k][$j] = $hdiff[$k][$j];
                            $compares_keys_y[] = $k;
                        }
                    }
                }
                if ($hit_counter == 2 && count($hdiff[$i][$j]) == 2) {
                    foreach (array_values($hdiff[$i][$j]) as $value) {
                        $column = null;
                        $stroke = 0;
                        foreach ($hdiff as $item) {
                            if (array_key_exists($j, $item)) {
                                $column[] = $stroke;
                            }
                            $stroke++;
                        }
                        foreach ($column as $key1) {
                            if ($key1 != $compares_keys_y[0] && $key1 != $i) {
                                foreach (array_keys($hdiff[$key1][$j]) as $key2) {
                                    if ($hdiff[$key1][$j][$key2] == $value) {
                                        unset($hdiff[$key1][$j][$key2]);
                                    }
                                }
                            }
                        }
                    }
                }
                if ($hit_counter == 3 && count($hdiff[$i][$j]) == 3) {
                    foreach (array_values($hdiff[$i][$j]) as $value) {
                        $column = null;
                        $stroke = 0;
                        foreach ($hdiff as $item) {
                            if (array_key_exists($j, $item)) {
                                $column[] = $stroke;
                            }
                            $stroke++;
                        }
                        foreach ($column as $key1) {
                            if ($key1 != $compares_keys_y[0] && $key1 != $i && $key1 != $compares_keys_y[1]) {
                                foreach (array_keys($hdiff[$key1][$j]) as $key2) {
                                    if ($hdiff[$key1][$j][$key2] == $value) {
                                        unset($hdiff[$key1][$j][$key2]);
                                    }
                                }
                            }
                        }
                    }
                }
                if ($hit_counter == 4 && count($hdiff[$i][$j]) == 4) {
                    foreach (array_values($hdiff[$i][$j]) as $value) {
                        $column = null;
                        $stroke = 0;
                        foreach ($hdiff as $item) {
                            if (array_key_exists($j, $item)) {
                                $column[] = $stroke;
                            }
                            $stroke++;
                        }
                        foreach ($column as $key1) {
                            if ($key1 != $compares_keys_y[0] && $key1 != $i && $key1 != $compares_keys_y[1] && $key1 != $compares_keys_y[2]) {
                                foreach (array_keys($hdiff[$key1][$j]) as $key2) {
                                    if ($hdiff[$key1][$j][$key2] == $value) {
                                        unset($hdiff[$key1][$j][$key2]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        //Удаляем пустые значения
        $duples_vert = $array_model->removeNullCells($duples_vert);

        //Находим дупликаты в квардратах


        //Теперь вставляем единственно возможные значения в изначальный массив

        for ($i = 0; $i < count($hdiff); $i++) {
            for ($j = 0; $j < count($hdiff); $j++) {
                if (count($hdiff[$i][$j]) == 1) {
                    $array[$i][$j] = array_values($hdiff[$i][$j])[0];
                }
            }
        }

        //Если остались незаполненные ячейки, исполняем функцию заново, до тех пор пока не заполнятся все ячейки
        for ($i = 0; $i < count($array); $i++) {
            for ($j = 0; $j < count($array); $j++) {
                if ($array[$i][$j] == "*") {
                    self::$func_counter++;
                    $array = $this->analyzeArrays($array);
                    break(2);
                }
            }
        }
//        echo $fc=self::$func_counter;
        return $array;

    }



//TODO:Обернуть все конструкции в функции, новый массив передать в начало и зациклить до получения готового массива
//        return $array;


//    public function getData($file)
//    {
//        $array=$this->putFileToArray($file);
//        $example_arr = [1, 2, 3, 4, 5, 6, 7, 8, 9];
//        for ($i = 0; $i < count ($array); $i++) {
//            $diff[$i] = array_diff($array[$i], $example_arr);
//        }
//    }
}