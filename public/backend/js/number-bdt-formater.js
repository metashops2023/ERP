const bdFormat = function(num) {
    const formatter = new Intl.NumberFormat('bn-Bd', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });

    const bnToEng = function(bnNumber) {
        return bnNumber.replace(/[০-৯]/g, function(bnDigit) {
            return "০১২৩৪৫৬৭৮৯".indexOf(bnDigit);
        });
    }

    const engToBn = function(num) {
        return num.replace(/\d/g, function(d) {
            return "০১২৩৪৫৬৭৮৯"[d];
        });
    }
    return bnToEng(formatter.format(num));
}
