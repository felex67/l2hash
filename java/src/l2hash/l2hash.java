package src.l2hash;
import java.util.HexFormat;
import java.util.Scanner;

public class l2hash {
    private static int bytes2int(byte raw[], int idx) {
        int i = raw[idx];
        i |= raw[idx + 1] << 8;
        i |= raw[idx + 2] << 16;
        i |= raw[idx + 3] << 24;
        return i;
    }
    public String encode(String s) {
        String result = "0x";
        byte key[] = new byte[16];
        byte dest[] = new byte[16];
        int work[] = new int[4];
        int len = s.length();
        for (int i = 0; i < 16; i++) {
            key[i] = dest[i] = i < len ? (byte)s.charAt(i) : 0;
        }
        work[0] = bytes2int(key, 0) * 213119 + 2529077;
        work[0] -= (work[0] / 4294967296L) * 4294967296L;
        work[1] = bytes2int(key, 4) * 213247 + 2529089;
        work[1] -= (work[1] / 4294967296L) * 4294967296L;
        work[2] = bytes2int(key, 8) * 213203 + 2529589;
        work[2] -= (work[2] / 4294967296L) * 4294967296L;
        work[3] = bytes2int(key, 12) * 213821 + 2529997;
        work[3] -= (work[3] / 4294967296L) * 4294967296L;
        for (int i = 0; i < 16; i += 4) {
            key[i] = (byte)(work[i >> 2]);
            key[i + 1] = (byte)(work[i >> 2] >> 8);
            key[i + 2] = (byte)(work[i >> 2] >> 16);
            key[i + 3] = (byte)(work[i >> 2] >> 24);
        }
        dest[0] ^= key[0];
        for (int i = 1; i < 16; i++) {
            dest[i] ^= (dest[i - 1] ^ key[i]);
        }
        for (int i = 0; i < 16; i++) {
            if (0 == dest[i]) dest[i] = 0x66;
            result += HexFormat.of().toHexDigits(dest[i]);
        }
        return result;
    }
    public static void main(String args[]) {
        Scanner console = new Scanner(System.in);
        l2hash hash = new l2hash();
        while (true) {
            System.out.print("Введите пароль или 'exit' для выхода: ");
            String pwd = console.nextLine();
            pwd.replace('\n', '\0');
            if (0 == pwd.compareTo("exit")) break;
            System.out.println("Хэш: " + hash.encode(pwd));
        }
        console.close();
        return;
    }
}

