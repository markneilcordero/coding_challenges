function validateEmail(email)
{
    const emailRegex = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

    return emailRegex.test(email);
}

console.log(validateEmail("test@example.com"));
console.log(validateEmail("invalid-email"));