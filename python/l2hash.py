from array import *
# @param raw - bytesarray
# @param off - offset index
def bytes2int(raw, off):
    i = raw[off] & 0xff
    i |= (raw[off + 1] & 0xff) << 8
    i |= (raw[off + 2] & 0xff) << 16
    i |= (raw[off + 3] & 0xff) << 24
    return i

# Lineage2 password hashing function used by PTS Authority server
# @author felex67
# @version v0.1b
# @param password - string containing password
# @returns string - hashed password. 0x... to paste in query
def l2hash(password):
    key = bytearray(range(16))
    dest = bytearray(range(16))
    ikey = array('L', range(4))
    pwdlen = len(password)
    result = '0x'

    for i in range(16):
        key[i] = dest[i] = 0

    for i in range(pwdlen):
        key[i] = dest[i] = ord(password[i])


    ikey[0] = 0xffffffff & (bytes2int(key, 0) * 213119 + 2529077)
    ikey[0] -= (ikey[0] // 4294967296) * 4294967296
    ikey[1] = 0xffffffff & (bytes2int(key, 4) * 213247 + 2529089)
    ikey[1] -= (ikey[1] // 4294967296) * 4294967296
    ikey[2] = 0xffffffff & (bytes2int(key, 8) * 213203 + 2529589)
    ikey[2] -= (ikey[2] // 4294967296) * 4294967296
    ikey[3] = 0xffffffff & (bytes2int(key, 12) * 213821 + 2529997)
    ikey[3] -= (ikey[3] // 4294967296) * 4294967296

    i = 0
    for ii in range(4):
        key[i] = ikey[i >> 2] & 0xff
        key[i + 1] = 0xff & ((ikey[i >> 2] & 0xff00) >> 8)
        key[i + 2] = 0xff & ((ikey[i >> 2] & 0xff0000) >> 16)
        key[i + 3] = 0xff & ((ikey[i >> 2] & 0xff000000) >> 24)
        i += 4

    dest[0] ^= key[0]
    for i in range(1, 16):
        dest[i] ^= (dest[i - 1] ^ key[i])

    for i in range(0, 16):
        if 0 == dest[i]: dest[i] = 0x66
        result += f"{dest[i]:0x}"

    return result

password = input('Пожалуйста введитее пароль: ')
print(l2hash(password))