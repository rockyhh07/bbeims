function show_alert(content = {}, callback = undefined) {
    const holder = $(`<div 
        id="alert-holder" 
        class="d-flex justify-content-center align-items-center" 
        style="position: fixed; top: 0; left: 0; z-index: 11000; width: 100%; height: 100vh; background-color: rgba(0,0,0,0.8);"
    ></div>`);
    const container = $(`<div class="card p-3 m-2" style="max-height: 90vh; overflow-y: auto;"></div>`);
    const header = $(`<h3 class="p-2" style="border-bottom: 1px solid rgba(0,0,0,0.6);">${content.title}</h3>`);
    const body = $(`<div class="mb-3 p-2">${content.body}</div>`);
    const footer = $(`<div class="d-flex justify-content-end" style="gap: 1rem;"></div>`);

    const btn_okay = $(`<button class="btn btn-primary">${content.buttons[0] ? content.buttons[0] : "Okay"}</button>`);
    const btn_cancel = content.buttons[1] ? $(`<button class="btn btn-danger">${content.buttons[1]}</button>`) : undefined;

    btn_okay.on("click", ()=>{
        callback ? callback(true) : null;
        holder.remove();
    });
    btn_cancel?btn_cancel.on("click", ()=>{
        callback ? callback(false) : null;
        holder.remove();
    }):null;

    footer.append(btn_okay, btn_cancel);
    container.append(header, body, footer);
    holder.append(container);
    $("body").append(holder);
}