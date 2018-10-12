<?php
/**
 * Created by PhpStorm.
 * User: legat
 * Date: 10.10.2018
 * Time: 14:47
 */

class MainModel extends Model
{

    public function putFileToArray($file)
    {
        $array = file($file, FILE_IGNORE_NEW_LINES); //Разбиваем файл на строки, без символа перевода строки в конце
        for ($i = 0; $i < count($array); $i++) {
            $array[$i] = explode(' ', $array[$i]);
        }

        $json = json_encode($array);
        header('Content-type: application/json');
        echo $json;

        $example_arr = [1, 2, 3, 4, 5, 6, 7, 8, 9];

        // Формирование массива возможных значений по горизонтали
        for ($i = 0; $i < count($array); $i++) {
            $keys[$i] = array_keys(array_diff($array[$i], $example_arr));
            $ediff[$i] = array_diff($example_arr, $array[$i]);
        }
        for ($i = 0; $i < count($ediff); $i++) {
            for ($j = 0; $j < count($ediff[$i]); $j++) {
                $nediff[$i][] = array_values($ediff[$i]);
            }
        }
        for ($i = 0; $i < count($array); $i++) {
            for ($j = 0; $j < count($keys[$i]); $j++) {
                $hdiff[$i][$keys[$i][$j]] = $nediff[$i][$j];
            }
        }
        echo $hdiff;

        //Формирование массива возможных значений по вертикали
        for ($i = 0; $i < count($array); $i++) {
            $column[$i] = array_column($array, $i);
        }

        for ($i = 0; $i < count($column); $i++) {
            $keys[$i] = array_keys(array_diff($column[$i], $example_arr));
            $ediff[$i] = array_diff($example_arr, $column[$i]);
        }
        $nediff = null;
        for ($i = 0; $i < count($ediff); $i++) {
            for ($j = 0; $j < count($ediff[$i]); $j++) {
                $nediff[$i][] = array_values($ediff[$i]);
            }
        }
        for ($i = 0; $i < count($array); $i++) {
            for ($j = 0; $j < count($keys[$i]); $j++) {
                $vdiff[$i][$keys[$i][$j]] = $nediff[$i][$j];
            }
        }
        echo $vdiff;
        //Приведение массива к горизонтальным координатам
        for ($i = 0; $i < count($vdiff); $i++) {
            $keys[$i] = array_keys($vdiff[$i]);
        }
        for ($i = 0; $i < count($vdiff); $i++) {
            foreach ($keys[$i] as $k) {
                $new_vdiff[$k][$i] = $vdiff[$i][$k];
            }
        }
        echo $new_vdiff;

        //Формирование массива возможных значений по квадратам
        $k = 0;
        for ($i = 0; $i < count($array); $i += 3) {
            for ($j = 0; $j < count($array); $j += 3) {
                $slice_1 = array_slice($array[$i], $j, 3);
                $slice_2 = array_slice($array[$i + 1], $j, 3);
                $slice_3 = array_slice($array[$i + 2], $j, 3);
                $square[$k] = array_merge($slice_1, $slice_2, $slice_3);
                $k++;
            }
        }

        for ($i = 0; $i < count($square); $i++) {
            $keys[$i] = array_keys(array_diff($square[$i], $example_arr));
            $ediff[$i] = array_diff($example_arr, $square[$i]);
        }
        $nediff = null;
        for ($i = 0; $i < count($ediff); $i++) {
            for ($j = 0; $j < count($ediff[$i]); $j++) {
                $nediff[$i][] = array_values($ediff[$i]);
            }
        }
        for ($i = 0; $i < count($array); $i++) {
            for ($j = 0; $j < count($keys[$i]); $j++) {
                $sdiff[$i][$keys[$i][$j]] = $nediff[$i][$j];
            }
        }
        echo $sdiff;

        //Преобразовываем массив по квадратам в горизонтальный массив
        $m = 0;
        $k = 0;
        $l = 0;
        for ($i = 0; $i < 9; $i++) {
            if ($i > 0 && $i % 3 == 0) {
                $l += 3;
                $m = 0;
            }
            $k = $k + $l;
            for ($j = 0; $j < 9; $j += 3) {
                $new_square[$i][$j] = $sdiff[$k][$m];
                $new_square[$i][$j + 1] = $sdiff[$k][$m + 1];
                $new_square[$i][$j + 2] = $sdiff[$k][$m + 2];
                $k++;
                if ($k % 3 == 0) {
                    $k = 0;
                    $m += 3;
                }
            }
        }

        //Удаляем пустые значения

        for ($i = 0; $i < count($new_square); $i++) {
            for ($j = 0; $j < count($new_square); $j++) {
                if ($new_square[$i][$j] == null) {
                    unset($new_square[$i][$j]);
                }
            }
        }
        echo $new_square;

        //Сравниваем массивы попарно

        for ($i = 0; $i < count($hdiff); $i++) {
            for ($j = 0; $j < count($hdiff[$i]); $j++) {
                $hdiff[$i][$j] = array_intersect($hdiff[$i][$j], $new_vdiff[$i][$j]);
            }
        }
        for ($i = 0; $i < count($hdiff); $i++) {
            for ($j = 0; $j < count($hdiff); $j++) {
                if ($hdiff[$i][$j] == null) {
                    unset($hdiff[$i][$j]);
                }
            }
        }
        echo $hdiff;
        for ($i = 0; $i < count($hdiff); $i++) {
            for ($j = 0; $j < count($hdiff[$i]); $j++) {
                $hdiff[$i][$j] = array_intersect($hdiff[$i][$j], $new_square[$i][$j]);
            }
        }
        for ($i = 0; $i < count($hdiff); $i++) {
            for ($j = 0; $j < count($hdiff); $j++) {
                if ($hdiff[$i][$j] == null) {
                    unset($hdiff[$i][$j]);
                }
            }
        }
        echo $hdiff;

        //Теперь вставляем единственно возможные значения в изначальный массив

        for ($i = 0; $i < count($hdiff); $i++) {
            for ($j = 0; $j < count($hdiff); $j++) {
                if (count($hdiff[$i][$j]) == 1) {
                    $array[$i][$j]=array_values($hdiff[$i][$j])[0];
                }
            }
        }
        echo $array;
//TODO:Обернуть все конструкции в функции, новый массив передать в начало и зациклить до получения готового массива
//        return $array;
    }

//    public function getData($file)
//    {
//        $array=$this->putFileToArray($file);
//        $example_arr = [1, 2, 3, 4, 5, 6, 7, 8, 9];
//        for ($i = 0; $i < count ($array); $i++) {
//            $diff[$i] = array_diff($array[$i], $example_arr);
//        }
//    }
}