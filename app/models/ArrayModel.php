<?php
/**
 * Created by PhpStorm.
 * User: legat
 * Date: 15.10.2018
 * Time: 8:05
 */

class ArrayModel
{
    private const EXAMPLE_ARR = [1, 2, 3, 4, 5, 6, 7, 8, 9];

    public function removeNullCells($array)
    {
        for ($i = 0; $i < count($array); $i++) {
            for ($j = 0; $j < count($array); $j++) {
                if ($array[$i][$j] == null) {
                    unset($array[$i][$j]);
                }
            }
        }
        return $array;
    }

    private function makeDifferenceFromExample($array)
    {
        //Вычисляем массив возможных значений
        $example_arr = ArrayModel::EXAMPLE_ARR;
        $nediff = null;

        for ($i = 0; $i < count($array); $i++) {
            $keys[$i] = array_keys(array_diff($array[$i], $example_arr));
            $ediff[$i] = array_diff($example_arr, $array[$i]);
        }
        for ($i = 0; $i < count($ediff); $i++) {
            for ($j = 0; $j < count($ediff[$i]); $j++) {
                $nediff[$i][] = array_values($ediff[$i]);
            }
        }
        $result = array($keys, $nediff);
        return $result;


    }

    public function makeHorizontalArray($array)
    {
        // Формирование массива возможных значений по горизонтали
        $result = $this->makeDifferenceFromExample($array);
        $keys = $result[0];
        $nediff = $result[1];
        //Приводим массив возможных значений к координатам исходного массива
        for ($i = 0; $i < count($array); $i++) {
            for ($j = 0; $j < count($keys[$i]); $j++) {
                $hdiff[$i][$keys[$i][$j]] = $nediff[$i][$j];
            }
        }

        return $hdiff;
    }

    public function makeVerticalArray($array)
    {
        //Формирование массива возможных значений по вертикали

        for ($i = 0; $i < count($array); $i++) {
            $column[$i] = array_column($array, $i);
        }

        $result = $this->makeDifferenceFromExample($column);
        $keys = $result[0];
        $nediff = $result[1];

        //Приводим массив возможных значений к координатам исходного массива
        for ($i = 0; $i < count($array); $i++) {
            for ($j = 0; $j < count($keys[$i]); $j++) {
                $vdiff[$i][$keys[$i][$j]] = $nediff[$i][$j];
            }
        }

        //Приведение массива к горизонтальным координатам
        for ($i = 0; $i < count($vdiff); $i++) {
            $keys[$i] = array_keys($vdiff[$i]);
        }
        for ($i = 0; $i < count($array); $i++) {
            foreach ($keys[$i] as $k) {
                $new_vdiff[$k][$i] = $vdiff[$i][$k];
            }
        }
        return $new_vdiff;
    }

    public function makeSquareArray($array)
    {
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

        $result = $this->makeDifferenceFromExample($square);
        $keys = $result[0];
        $nediff = $result[1];

        for ($i = 0; $i < count($array); $i++) {
            for ($j = 0; $j < count($keys[$i]); $j++) {
                $sdiff[$i][$keys[$i][$j]] = $nediff[$i][$j];
            }
        }

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

        $new_square = $this->removeNullCells($new_square);
        return $new_square;
    }
}