import java.util.*;
import java.io.*;
public class Practice {
public static int[] bubbleSortArray(int[] arr) {
for(int i = 0; i < arr.length; i++) {
        for(int j = 0; j < arr.length; j++) {
            if(arr[i] < arr[j]) {
                int temp = arr[i];
                arr[i] = arr[j];
                arr[j] = temp;
            }
        }
    }
    return arr;
}
public static void main(String args[]) {
System.out.println(Arrays.toString(bubbleSortArray(new int[]{1,5,2,4,3})));
}
}