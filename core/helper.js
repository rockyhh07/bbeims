export class Helper {

  static DataTable_Reset(tableElemenet = '') {
    if ($.fn.DataTable.isDataTable(tableElemenet)) $(tableElemenet).DataTable().destroy();
    $(tableElemenet).html("");
  }

  static DataTable_Init(tableElemenet = '', body = '', bindCallBackBeforeInitOfDataTable = () => { }, bindCallBackAfterInitOfDataTable = () => { }) {
    $(tableElemenet).append(body);
    bindCallBackBeforeInitOfDataTable();
    $(tableElemenet).DataTable({ bAutoWidth: false, autoWidth: false });
    bindCallBackAfterInitOfDataTable();
  }

  static getAge(dateString) {
    if (!dateString) return 0;
    var today = new Date();
    var birthDate = new Date(dateString);
    var age = today.getFullYear() - birthDate.getFullYear();
    var m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
      age--;
    }
    return age;
  }

  static Promt_Error = (msg) => this.f(".error-msg", true)().map(el => el.innerHTML = `<b>Error: </b>${msg}`);
  static Promt_Clear = () => this.f(".error-msg", true)().map(el => el.innerHTML = '');

  static f = (element, mulitple = false) =>
    !mulitple ? document.querySelector(element) : () => {
      const elems = [];
      document.querySelectorAll(element).forEach(el => elems.push(el));
      return elems;
    };
}