export class ModalHandler {

  static onShow = (element, callback) => $(element).on('show.bs.modal', callback);
  static whenShown = (element, callback) => $(element).on('shown.bs.modal', callback);
  static onHide = (element, callback) => $(element).on('hide.bs.modal', callback);
  static whenHidden = (element, callback) => $(element).on('hidden.bs.modal', callback);

}