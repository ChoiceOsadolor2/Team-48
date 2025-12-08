public class Math {

    // Constructor (optional, but corrected to match class name)
    public Math() {}

    public int max(int num1, int num2) {
        if (num1 > num2) {
            return num1;
        } else {
            return num2;
        }
    }

    public int min(int num1, int num2) {
        if (num1 < num2) {
            return num1;
        } else {
            return num2;
        }
    }

    public int add(int num1, int num2) {
        return num1 + num2;
    }

    public int sub(int num1, int num2) {
        return num1 - num2;
    }

    public int multi(int num1, int num2) {
        return num1 * num2;
    }

    public int divide(int num1, int num2) {
        return num1 / num2;
    }

    public int mod(int num1, int num2) {
        return num1 % num2;
    }

    public double pow(int num1, int num2) {
        return Math.pow(num1, num2);
    }
}
