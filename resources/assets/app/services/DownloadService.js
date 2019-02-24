/**
 * Service that can initialize file downloading based on existing binary data.
 */
export default {
  /**
   * Initiates file downloading.
   *
   * @param {String} data File content to download
   * @param {String} fileName File name that will be visible to user
   *
   * @throws Error
   */
  downloadAsFile(data, fileName) {
    const url = window.URL.createObjectURL(new Blob([data]));
    const link = document.createElement('a');

    link.href = url;
    link.setAttribute('download', fileName);
    document.body.appendChild(link);
    link.click();
  },
};
