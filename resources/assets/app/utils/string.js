/**
 * String helpers.
 */
export default {
  /**
   * Converts CamelCased string into snake_cased.
   *
   * @param {string} src String to convert
   *
   * @return {string} Result string
   */
  snakeCase(src) {
    return src.replace(/([A-Z])/g, $1 => `_${$1.toLowerCase()}`);
  },
};
