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
    app.config.globalProperties.$formatDateDMY= function (dateString) {
      const date = new Date(dateString);
      const day = String(date.getDate()).padStart(2, '0');
      const month = String(date.getMonth() + 1).padStart(2, '0'); // Months are zero-based
      const year = date.getFullYear();
      
      return `${day}/${month}/${year}`;
    };
    app.config.globalProperties.$capFirst= function (string) {
      if(string != null)
        return string.charAt(0).toUpperCase() + string.slice(1);
      else
        return "";
    };
  }
};
