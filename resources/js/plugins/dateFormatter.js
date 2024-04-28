// dateFormatter.js
export default {
  install(app) {
    app.config.globalProperties.$formatDate = function(dateString) {
      const date = new Date(dateString);
      const formattedDate = date.toLocaleDateString('en-US', {
        year: 'numeric',
        month: '2-digit',
        day: '2-digit',
      });
      return formattedDate;
    };
  }
};
