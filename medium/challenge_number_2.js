function countWords(str)
{
    const words = str.toLowerCase().split(' ');
    const wordCount = {};

    words.forEach(word => {
        wordCount[word] = (wordCount[word] || 0) + 1;
    });

    return wordCount;
}

console.log(countWords("apple banana apple")); // Output: { apple: 2, banana: 1}