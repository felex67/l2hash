
let pwd;
let hash;

function bytes2int(raw, off) {
    let i = 0xff & raw[off];
    i |= (0xff & raw[off + 1]) << 8;
    i |= (0xff & raw[off + 2]) << 16;
    i |= (0xff & raw[off + 3]) << 24;
    return i;
}
/**
 * l2 hashing function
 * @param {String} pwd
 * @returns {String} hex encoded hash string
 */
function l2hash(pwd) {
    let key = [];
    let dest = [];
    let a = [];
    let result = '0x';
    for (let i = 0; i < 16; i++) {
        key[i] = dest[i] = (i < pwd.length) ? pwd.charCodeAt(i) : 0;
    }

    a[0] = bytes2int(key, 0) * 213119 + 2529077;
    a[0] -= Number.parseInt(a[0] / 4294967296) * 4294967296;
    a[1] = bytes2int(key, 4) * 213247 + 2529089;
    a[1] -= Number.parseInt(a[1] / 4294967296) * 4294967296;
    a[2] = bytes2int(key, 8) * 213203 + 2529589;
    a[2] -= Number.parseInt(a[2] / 4294967296) * 4294967296;
    a[3] = bytes2int(key, 12) * 213821 + 2529997;
    a[3] -= Number.parseInt(a[3] / 4294967296) * 4294967296;

    for (let i = 0; i < 16; i += 4) {
        key[i] = (a[i >> 2] & 0xff);
        key[i + 1] = (0xff & ((a[i >> 2] & 0xff00) >> 8));
        key[i + 2] = (0xff & ((a[i >> 2] & 0xff0000) >> 16));
        key[i + 3] = (0xff & ((a[i >> 2] & 0xff000000) >> 24));
    }
    dest[0] ^= key[0];
    for (let i = 1; i < 16; i++) {
        dest[i] ^= (dest[i - 1] ^ key[i]);
    }
    for (let i = 0; i < 16; i++) {
        result += dest[i].toString(16);
    }
    return result;
}
function init() {
    pwd = document.getElementById('pwd');
    pwd.maxLength = 16;
    hash = document.getElementById('hash');
    pwd.onkeyup = () => {
        if (pwd.value.length > 3) {
            if ((/^[A-Z0-9]{4,16}$/i).test(pwd.value)) {
                hash.value = l2hash(pwd.value);
            }
        }
    }
}