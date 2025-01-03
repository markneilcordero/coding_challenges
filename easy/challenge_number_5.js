function countVowels(str)
{
    const vowels = str.toLowerCase().match(/[aeiou]/g);
    return vowels ? vowels.length : 0;
}

console.log(countVowels("Hello World")); // Output: 3 (e, o, o)
console.log(countVowels("JavaScript")); // Output: 3 (a, a, i)
console.log(countVowels("Rhythm")); // Output: 0