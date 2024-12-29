function validateEmail(email)
{
    const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return regex.test(email);
}

console.log(validateEmail("test@example.com")); // Output: true
console.log(validateEmail("invalid-email")); // Output: false
console.log(validateEmail("test@.com")); // Output: false
console.log(validateEmail("name@domain.co")); // Output: true