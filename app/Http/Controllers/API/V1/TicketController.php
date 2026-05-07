<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use App\Events\TicketCreated;
use App\Events\TicketUpdated;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class TicketController extends Controller
{
    /**
     * Get all tickets with pagination.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $perPage = $request->query('per_page', 15);
            $tickets = Ticket::with(['comments', 'comments.user'])
                ->paginate($perPage);

            return response()->json([
                'status' => 'success',
                'data' => $tickets->items(),
                'pagination' => [
                    'total' => $tickets->total(),
                    'per_page' => $tickets->perPage(),
                    'current_page' => $tickets->currentPage(),
                    'last_page' => $tickets->lastPage(),
                    'from' => $tickets->firstItem(),
                    'to' => $tickets->lastItem(),
                ],
                'message' => 'Tickets retrieved successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => 'Error retrieving tickets: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get a single ticket by ID with its comments.
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        try {
            $ticket = Ticket::with(['comments', 'comments.user', 'category'])
                ->find($id);

            if (!$ticket) {
                return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'message' => 'Ticket not found.'
                ], 404);
            }

            return response()->json([
                'status' => 'success',
                'data' => $ticket,
                'message' => 'Ticket retrieved successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => 'Error retrieving ticket: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created ticket via API.
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        try {
            // 1. Validation
            $validated = $request->validate([
                'customer_name' => 'required|string|max:200',
                'email'         => 'required|email',
                'description'   => 'required|string',
                'phone'         => 'nullable|string|max:20',
                'category_id'   => 'nullable|exists:categories,id',
            ]);

            // 2. Prepare Data
            $data = [
                'customer_name' => $validated['customer_name'],
                'email'         => $validated['email'],
                'phone'         => $validated['phone'] ?? null,
                'description'   => $validated['description'],
                'category_id'   => $validated['category_id'] ?? null,
                'ref'           => sha1(time() . $validated['email']),
                'status'        => 0, // 0 = New/Pending
            ];

            // 3. Create Ticket
            $ticket = Ticket::create($data);

            // 4. Trigger Event (sends email)
            TicketCreated::dispatch($ticket);

            return response()->json([
                'status'  => 'success',
                'data'    => $ticket,
                'message' => 'Ticket created successfully. Reference: ' . $data['ref']
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status'  => 'error',
                'data'    => null,
                'message' => 'Error creating ticket: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update an existing ticket.
     * 
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        try {
            $ticket = Ticket::find($id);

            if (!$ticket) {
                return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'message' => 'Ticket not found.'
                ], 404);
            }

            // Validation
            $validated = $request->validate([
                'status'      => 'nullable|integer|in:0,1,2,3',
                'category_id' => 'nullable|exists:categories,id',
            ]);

            // Update only if values are provided
            if (isset($validated['status'])) {
                $ticket->status = $validated['status'];
            }
            if (isset($validated['category_id'])) {
                $ticket->category_id = $validated['category_id'];
            }

            $ticket->save();

            // Dispatch update event
            TicketUpdated::dispatch($ticket);

            return response()->json([
                'status' => 'success',
                'data' => $ticket,
                'message' => 'Ticket updated successfully.'
            ], 200);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => 'Validation failed.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => 'Error updating ticket: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete a ticket.
     * 
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        try {
            $ticket = Ticket::find($id);

            if (!$ticket) {
                return response()->json([
                    'status' => 'error',
                    'data' => null,
                    'message' => 'Ticket not found.'
                ], 404);
            }

            $ticket->delete();

            return response()->json([
                'status' => 'success',
                'data' => null,
                'message' => 'Ticket deleted successfully.'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'data' => null,
                'message' => 'Error deleting ticket: ' . $e->getMessage()
            ], 500);
        }
    }
}