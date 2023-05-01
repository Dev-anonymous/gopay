class DemandeTransfert {
  final int id;
  final String montant;
  final String destination;
  final String status;
  final String note;
  final String date;
  DemandeTransfert(
      {required this.id,
      required this.montant,
      required this.destination,
      required this.status,
      required this.note,
      required this.date});

  factory DemandeTransfert.fromJson(Map<String, dynamic> json) {
    return DemandeTransfert(
        id: json['id'],
        montant: json['montant'],
        destination: json['au_numero'],
        status: json['status'],
        note: json['note_validation'] ?? '',
        date: json['date']);
  }
}
