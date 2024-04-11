
/**
 * Get random image from picsum.photos
 * @param {number} min - Minimum number
 * @param {number} max - Maximum number
 * @returns url
 */
export const getPicsumRandomImage = (min = 0, max = 100) => {
    var random = Math.floor(Math.random() * (max - min + 1)) + min;

    return `https://picsum.photos/200/300?random=${random}`;
};