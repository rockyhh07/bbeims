export class Helper {

  static px_to_mm(px) {
    return Math.floor(Number(px) * 0.2645833333);
  }

  static getCategory(categoryID) {
    switch (categoryID) {
      case 'A': return 'Admin';
      case 'S': return 'Staff';
      default: return '';
    }
  }

  static getGender(genderID = '') {
    switch (genderID) {
      case 'M': return 'MALE';
      case 'F': return 'FEMALE';
      default: return '';
    }
  }

  static toInputDate(string_date = '') {
    const date = new Date(string_date);
    const y = date.getFullYear();
    const m = date.getMonth() + 1;
    const d = date.getDate();
    return `${y}-${m < 10 ? '0' + m : m}-${d < 10 ? '0' + d : d}`
  }

  static getCivilStatus(civilStatusID = '') {
    switch (civilStatusID) {
      case 'S': return 'SINGLE';
      case 'M': return 'MARRIED';
      case 'SP': return 'SEPARATED';
      case 'W': return 'WIDOWED';
      default: return '';
    }
  }

  static AsID(id, padding_count = 0, padding_char = '', start = '', end = '') {
    return `${start}${String(id).padStart(padding_count, padding_char)}${end}`
  }

  static ObjectToArray(obj) {
    return Object.entries(obj).map(([name, value]) => ({ name, value }));
  }

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