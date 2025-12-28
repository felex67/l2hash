<?php
/**
 * php implementation of Lineage2 hash function for encrypting passwords and other
 * sensitive data like answers on quiz that is used by PTS Authority server
 * @version 0.1b
 * @author felexxx67@yandex.ru
 */
    function bytes2int($array, $offset) {
        $int = $array[$offset] & 0xff;
        $int |= ($array[$offset + 1] & 0xff) << 8;
        $int |= ($array[$offset + 2] & 0xff) << 16;
        $int |= ($array[$offset + 3] & 0xff) << 24;
        echo('int:'.$int.'</br>');
        return $int;
    }

    function l2hash($pwd) {
        $key = array_fill(0, 16, 0);
        $destbytes = array_fill(0, 16, 0);
        $ints = array_fill(0, 4, 0);
        $pwdlen = strlen($pwd);
        $encrypt = '0x';

        for ($i = 0; $i < $pwdlen; $i++) {
            $key[$i] = $destbytes[$i] = ord($pwd[$i]);
        }

        $ints[0] = bytes2int($key, 0) * 213119 + 2529077;
        $ints[0] -= (intdiv($ints[0], 4294967296) * 4294967296);
        $ints[1] = bytes2int($key, 4) * 213247 + 2529089;
        $ints[1] -= intdiv($ints[1], 4294967296) * 4294967296;
        $ints[2] = bytes2int($key, 8) * 213203 + 2529589;
        $ints[2] -= intdiv($ints[2], 4294967296) * 4294967296;
        $ints[3] = bytes2int($key, 12) * 213821 + 2529997;
        $ints[3] -= intdiv($ints[3], 4294967296) * 4294967296;

        for ($i = 0; $i < 16; $i += 4) {
            $key[$i + 0] = 0xff & $ints[$i >> 2];
            $key[$i + 1] = 0xff & ($ints[$i >> 2] >> 8);
            $key[$i + 2] = 0xff & ($ints[$i >> 2] >> 16);
            $key[$i + 3] = 0xff & ($ints[$i >> 2] >> 24);
        }

        $destbytes[0] ^= $key[0];
        for ($i = 1; $i < 16; $i++) {
            $destbytes[$i] ^= ($destbytes[$i - 1] ^ $key[$i]);
        }

        for ($i = 0; $i < 16; $i++) {
            if (0 == $destbytes[$i]) $destbytes[$i] = 0x66;
            $encrypt .= sprintf('%02X', $destbytes[$i]);
        }
        return $encrypt;
    }
?>