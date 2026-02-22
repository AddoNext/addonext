/**
 * Converts a hex color code to a rgba() string with specified alpha.
 * @param hex - Hex color (e.g., "#ff0000" or "ff0000" or "#f00")
 * @param alpha - Alpha value between 0 (transparent) and 1 (opaque)
 */
export function hexToRgba(hex: string, alpha: number): string {
  // Remove possible leading "#"
  let cleanHex = hex.replace(/^#/, '');

  // Convert short 3-digit form to 6-digit
  if (cleanHex.length === 3) {
    cleanHex = cleanHex
      .split('')
      .map((char) => char + char)
      .join('');
  }

  const r = parseInt(cleanHex.slice(0, 2), 16);
  const g = parseInt(cleanHex.slice(2, 4), 16);
  const b = parseInt(cleanHex.slice(4, 6), 16);

  if (
    isNaN(r) || isNaN(g) || isNaN(b) ||
    cleanHex.length !== 6 ||
    alpha < 0 || alpha > 1
  ) {
    // fallback for invalid input
    return `rgba(0,0,0,${alpha})`;
  }

  return `rgba(${r},${g},${b},${alpha})`;
}