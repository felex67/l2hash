#pragma once

#ifdef __cplusplus
extern "C" {
#endif
/**
 * Lineage2 password hashing function
 * @param _Dest - char buffer must be atleast 35 bytes
 * @param _Pwd - source string to hash, 4-16 bytes
 * @return const char* - pointer to _Dest buffer
 * @author felex67
 * @version 0.1b
 */
const char* l2pwdhash(char* _Dest, const char* _Pwd);

#ifdef __cplusplus
}
#endif
