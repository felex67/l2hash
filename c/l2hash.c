#include <stdio.h>
#include <memory.h>

#include "l2hash.h"

const char* l2pwdhash(char* _Dest, const char* _Pwd) {
    union {
        unsigned int u32[4];
        unsigned char u8[16];
        char c[16];
    } dest, key;
    unsigned int a[4];
    char* ptr = NULL;
    memset(&dest, 0, sizeof(dest));
    memset(&key, 0, sizeof(key));
    memset(a, 0, sizeof(a));
    for (int i = 0; i < sizeof(key); i++) {
        dest.c[i] = key.c[i] = _Pwd[i];
        if (!_Pwd[i]) break;
    }
    a[0] = key.u32[0] * 213119 + 2529077;
    a[0] -= (a[0] / 4294967296) * 4294967296;
    a[1] = key.u32[1] * 213247 + 2529089;
    a[1] -= (a[1] / 4294967296) * 4294967296;
    a[2] = key.u32[2] * 213203 + 2529589;
    a[2] -= (a[2] / 4294967296) * 4294967296;
    a[3] = key.u32[3] * 213821 + 2529997;
    a[3] -= (a[3] / 4294967296) * 4294967296;

    memcpy(&key, a, sizeof(a));

    dest.u8[0] ^= key.u8[0];
    for (int i = 1; i < 16; i++) {
        dest.u8[i] ^= dest.u8[i - 1] ^ key.u8[i];
    }
    sprintf(_Dest, "0x");
    ptr = _Dest + 2;
    for (int i = 0; i < 16; i++) {
        if (!dest.u8[i]) dest.u8[i] = 0x66;
        sprintf(ptr, "%0X", dest.u8[i]);
        ptr += 2;
    }
    return _Dest;
}