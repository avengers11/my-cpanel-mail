import Luhn from 'luhn-js';

// console.log(Luhn.isValid('44542738505150162'));
// console.log(Luhn.isValid('44540661970241257'))
// console.log(Luhn.generate('44540661970241'));
// console.log(Luhn.getRemainder('44543353847144279'));



// Generate all possible valid cards for a given BIN
function generateAllValidCards(bin) {
    if (!/^\d{6,15}$/.test(bin)) {
        throw new Error('BIN must be between 6 to 15 digits long.');
    }
    const base = bin;
    const maxLength = 16;

    // Generate card numbers
    const generatedCards = [];
    const start = Math.pow(10, maxLength - base.length - 1);
    const end = Math.pow(10, maxLength - base.length) - 1;

    for (let i = start; i <= end; i++) {
        const cardNumber = base + i.toString().padStart(maxLength - base.length, '0');
        generatedCards.push("'"+cardNumber);

        // if (Luhn.isValid(cardNumber)) {
        //     generatedCards.push("'"+cardNumber);
        // }
    }

    // Display only Luhn-valid cards in the textarea
    return generatedCards.join('\n');
}


// Generate all possible valid cards for a given BIN
function generateAllValidCards(bin) {
    if (!/^\d{6,15}$/.test(bin)) {
        throw new Error('BIN must be between 6 to 15 digits long.');
    }
    const base = bin;
    const maxLength = 16;

    // Generate card numbers
    const generatedCards = [];
    const start = Math.pow(10, maxLength - base.length - 1);
    const end = Math.pow(10, maxLength - base.length) - 1;

    console.log("start"+start+"");
    

    for (let i = start; i <= end; i++) {
        const cardNumber = base + i.toString().padStart(maxLength - base.length, '0');
        generatedCards.push("'"+cardNumber);

        // if (Luhn.isValid(cardNumber)) {
        //     generatedCards.push("'"+cardNumber);
        // }
    }

    // Display only Luhn-valid cards in the textarea
    return generatedCards.join('\n');
}


$(document).on("click", "#card_generate", function(){
    $("#card_generate").html(`<i class="mdi mdi mdi-autorenew mr-2"></i> Loading...`);
    // Example usage:
    const bin = $("#card_number").val();
    const validCards = generateAllValidCards(bin);

    console.log(validCards);
    
    $("#generated_card").val(validCards);
    $("#card_generate").html(`<i class="mdi mdi mdi-autorenew mr-2"></i> Generate`);
});