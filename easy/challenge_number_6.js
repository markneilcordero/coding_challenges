function toTitleCase(str)
{
    return str
        .split(' ')
        .map(word => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
        .join(' ');
}

// Test Case
console.log(toTitleCase("web development is fun")); // Output: "Web Development Is Fun"
console.log(toTitleCase("hello world")); // Output: "Hello World"
console.log(toTitleCase("javaScript is amazing")); // Output: "JavaScript Is Amazing"