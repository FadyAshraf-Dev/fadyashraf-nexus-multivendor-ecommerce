const ELEMENTS = {
    form: "addProductForm",

    costPrice: "inputCostPrice",
    sellingPrice: "inputSellingPrice",

    stockQuantity: "inputStockQuantity",
    lowStock: "inputLowStockThreshold",

    discountType: "selectDiscountType",
    discountValue: "inputDiscountValue",

    lastStep: "step4"
};
const dom = {
    form: document.getElementById(ELEMENTS.form),

    costPrice: document.getElementById(ELEMENTS.costPrice),
    sellingPrice: document.getElementById(ELEMENTS.sellingPrice),

    stockQuantity: document.getElementById(ELEMENTS.stockQuantity),
    lowStock: document.getElementById(ELEMENTS.lowStock),

    discountType: document.getElementById(ELEMENTS.discountType),
    discountValue: document.getElementById(ELEMENTS.discountValue),
    lastStep: document.getElementById(ELEMENTS.lastStep)

};

document.addEventListener("DOMContentLoaded", initializePage);

function initializePage() {

    const dom = createDom(ELEMENTS);

    initializeFormValidation(dom.form);

    initializeWizardNavigation(dom);

    initializeScrollBehavior();

    initializeDiscountToggle(dom);

    initializeDynamicConstraints(dom);

    initializeFormSubmission(dom);
};
