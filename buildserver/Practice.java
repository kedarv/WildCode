import java.util.*;
import java.io.*;
public class Practice {
public static int fibonacci(int n1) {
    if (n1 == 1) {
        return 0;
    }
    if (n1 == 2) {
        return 1;
    }
    return fibonacci(n1 - 1) + fibonacci(n1 - 2);
}
public static void main(String args[]) {
System.out.println(fibonacci(1));System.out.println(fibonacci(2));System.out.println(fibonacci(6));
}
}