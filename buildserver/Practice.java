import java.util.*;
import java.io.*;
public class Practice {
public static String reverse(String text) {
return new StringBuilder(text).reverse().toString();
}
public static void main(String args[]) {
System.out.println(reverse("hello"));
}
}