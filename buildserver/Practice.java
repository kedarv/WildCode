import java.util.*;
import java.io.*;
public class Practice {
public static boolean areAllCharactersUnique(String str) {
    if(str == null)
        return true;

    HashMap<Character, Integer> map = new HashMap<>();
    for(int i = 0; i < str.length(); i++) {
        if(map.get(str.charAt(i)) != null) {
            return false;
        } else {
            map.put(str.charAt(i), 1);
        }
    }
    return true;
}
public static void main(String args[]) {
System.out.println(areAllCharactersUnique("testing"));System.out.println(areAllCharactersUnique("hey"));System.out.println(areAllCharactersUnique("lol"));System.out.println(areAllCharactersUnique("nice"));
}
}