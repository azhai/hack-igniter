<?php
/**
 * hack-igniter.
 *
 * A example project extends of CodeIgniter v3.x
 *
 * @author  Ryan Liu (azhai)
 *
 * @see    http://azhai.surge.sh/
 *
 * @copyright   Copyright (c) 2013
 * @license http://opensource.org/licenses/MIT  MIT License
 */

namespace Mylib\Util;

$loader = load_class('Loader', 'core');
$loader->helper('alg');

/**
 * Geohash，使用Hilbert空间算法.
 *
 * $point = ['lng' => 113.95196, 'lat' => 22.541497];
 * $gh = new Geo_hilbert($point);
 * echo $gh->encode(); //2313000100002333212012
 * echo $gh->get_prefix(10 * 1000); //10km的前缀为 2313000100
 * $lng2 = 117.30; $lat2 = 39.94;
 * $gh2 = new Geo_hilbert($lng2, $lat2);
 * echo $gh->get_around_distance($gh2->encode()); //1250943.0m
 * echo $gh->get_accuracy_distance($lng2, $lat2); //1959404.42m
 */
class Geo_hilbert
{
    public const BITS_PER_CHAR = 2;
    public const EARTH_RADIUS = 6367000.0; //地球半径
    //经纬度范围
    public const LAT_MIN = -90.0;
    public const LAT_MAX = 90.0;
    public const LNG_MIN = -180.0;
    public const LNG_MAX = 180.0;

    //误差表，单位：m
    public static $prec_errors = [
        20015087, //20015.087 km
        10007543, //10007.543 km
        5003772, // 5003.772 km
        2501886, // 2501.886 km
        1250943, // 1250.943 km
        625471, //  625.471 km
        312736, //  312.736 km
        156368, //  156.368 km
        78184, //   78.184 km
        39092, //   39.092 km
        19546, //   19.546 km
        9772.99, // 9772.992  m
        4886.50, // 4886.496  m
        2443.25, // 2443.248  m
        1221.62, // 1221.624  m
        610.81, //  610.812  m
        305.41, //  305.406  m
        152.70, //  152.703  m
        76.35, //   76.351  m
        38.18, //   38.176  m
        19.09, //   19.088  m
        9.54, //  954.394 cm
        4.77, //  477.197 cm
    ];
    //经纬度
    public $lng = 0.0;
    public $lat = 0.0;
    public $code = '';

    public function __construct($lng = 0, $lat = 0)
    {
        if (\is_array($lng)) {
            $lat = isset($lng['lat']) ? $lng['lat'] : 0;
            $lng = isset($lng['lng']) ? $lng['lng'] : 0;
        }
        $this->update($lng, $lat);
    }

    public function update($lng, $lat)
    {
        \assert($lng >= self::LNG_MIN && $lng <= self::LNG_MAX);
        $this->lng = $lng;
        \assert($lat >= self::LAT_MIN && $lat <= self::LAT_MAX);
        $this->lat = $lat;
    }

    public static function encode_int4($code)
    {
        $_BASE4 = '0123';
        $code_size = (int) (log($code, 2)) + 2;
        $code_len = floor($code_size / 2);
        $res = array_fill(0, $code_len, '0');
        for ($i = $code_len - 1; $i >= 0; --$i) {
            $res[$i] = $_BASE4[$code & 0b11];
            $code = $code >> 2;
        }

        return implode('', $res);
    }

    public static function rotate($n, $x, $y, $rx, $ry)
    {
        if (0 === $ry) {
            if (1 === $rx) {
                $x = $n - 1 - $x;
                $y = $n - 1 - $y;
            }

            return [$y, $x];
        }

        return [$x, $y];
    }

    public static function coord2int($lng, $lat, $dim)
    {
        \assert($dim >= 1);
        $lat_y = ($lat + self::LAT_MAX) / 180.0 * $dim; //[0 ... dim)
        $lng_x = ($lng + self::LNG_MAX) / 360.0 * $dim; //[0 ... dim)

        return [
            min($dim - 1, floor($lng_x)),
            min($dim - 1, floor($lat_y)),
        ];
    }

    public static function xy2hash($x, $y, $dim)
    {
        $d = 0;
        $lvl = $dim >> 1;
        while ($lvl > 0) {
            $rx = (int) (($x & $lvl) > 0);
            $ry = (int) (($y & $lvl) > 0);
            $d += $lvl * $lvl * ((3 * $rx) ^ $ry);
            @list($x, $y) = self::rotate($lvl, $x, $y, $rx, $ry);
            $lvl = $lvl >> 1;
        }

        return $d;
    }

    /**
     * 计算哈希值
     *
     * @param mixed $prec
     */
    public function encode($prec = 0)
    {
        $max_index = \count(self::$prec_errors) - 1;
        if ($prec <= 0 || $prec > $max_index) {
            $prec = $max_index;
        }
        $dim = 1 << (($prec * self::BITS_PER_CHAR) >> 1);
        @list($x, $y) = self::coord2int($this->lng, $this->lat, $dim);
        $code = self::encode_int4(self::xy2hash($x, $y, $dim));

        return $this->code = sprintf('%0'.$prec.'s', $code);
    }

    /**
     * 获取满足条件的相同前缀
     *
     * @param mixed $distance
     */
    public function get_prefix($distance = 5000)
    {
        $errs = &self::$prec_errors;
        $compare = function ($target, $middle) use ($errs) {
            return $errs[$middle] - $target;
        };
        $right = \count($errs) - 1;
        $idx = binary_search($distance, $right, $compare);
        if (empty($this->code) && $this->lat) {
            $this->encode();
        }

        return substr($this->code, 0, $idx);
    }

    /**
     * 计算大致距离，单位：米.
     *
     * @param mixed $another_code
     */
    public function get_around_distance($another_code)
    {
        if (empty($this->code) && $this->lat) {
            $this->encode();
        }
        $same = get_similar_len($this->code, $another_code);
        $max_index = \count(self::$prec_errors) - 1;
        if ($same > $max_index) {
            $same = $max_index;
        }

        return self::$prec_errors[$same];
    }

    /**
     * 计算准确距离，单位：米.
     *
     * @param mixed $lng
     * @param mixed $lat
     */
    public function get_accuracy_distance($lng, $lat)
    {
        //计算三个参数
        $dx = $this->lng - (float) $lng;
        $dy = $this->lat - (float) $lat;
        $avg = ($this->lat + (float) $lat) / 2.0; //平均维度
        //计算东西方向距离和南北方向距离(单位：米)，东西距离采用三阶多项式
        $lx = self::EARTH_RADIUS * deg2rad($dx) * cos(deg2rad($avg)); // 东西距离
        $ly = self::EARTH_RADIUS * deg2rad($dy); // 南北距离
        //用平面的矩形对角距离公式计算总距离
        return sqrt($lx * $lx + $ly * $ly);
    }
}
