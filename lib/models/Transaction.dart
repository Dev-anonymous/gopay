class Transaction {
  final int id;
  final Map<String, dynamic> operateur;
  final double montant;
  final String transactionId;
  final String type;
  final String source;
  final String date;
  Transaction(
      {required this.id,
      required this.operateur,
      required this.montant,
      required this.transactionId,
      required this.type,
      required this.source,
      required this.date});

  factory Transaction.fromJson(Map<String, dynamic> json) {
    return Transaction(
        id: json['id'],
        operateur: json['operateur'],
        montant: double.parse(json['montant'].toString().split(' ')[0]),
        transactionId: json['trans_id'],
        type: json['type'],
        source: json['source'],
        date: json['date']);
  }
}
