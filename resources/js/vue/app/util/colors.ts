export const isValidHexColor = (color: string) => /^#([A-Fa-f0-9]{6}|[A-Fa-f0-9]{3})$/.test(color);
export const getValidHexColor = (color: string, fallback = "") =>
  isValidHexColor(color) ? color : isValidHexColor(fallback) ? fallback : "#fff";
