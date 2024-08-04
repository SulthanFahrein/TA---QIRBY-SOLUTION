import 'package:flutter/material.dart';

class TextButtonCustom extends StatelessWidget {
  final String label;
  final VoidCallback onTap;

  const TextButtonCustom({Key? key, required this.label, required this.onTap}) : super(key: key);

  @override
  Widget build(BuildContext context) {
    return GestureDetector(
      onTap: onTap,
      child: Text(
        label,
        style: Theme.of(context).textTheme.bodyLarge!.copyWith(
          fontWeight: FontWeight.bold,
          color: Colors.grey,
        ),
      ),
    );
  }
}
