function removeDuplicates(arr)
{
    return [...new Set(arr)];
}

console.log(removeDuplicates([1, 2, 2, 3, 4, 4, 5])); // Output: [1, 2, 3, 4, 5];
console.log(removeDuplicates(['apple', 'orange', 'apple'])); // Output: ['apple', 'orange'];